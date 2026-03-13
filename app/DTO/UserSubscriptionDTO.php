<?php

namespace App\DTO;

class UserSubscriptionDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public ?int $subscription_id = null,
        public ?int $used_properties = null,
        public ?string $started_at = null,
        public ?string $expires_at = null,
        public ?bool $active = null,
    ) {}

    /**
     * Create DTO from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            user_id: isset($data['user_id']) ? (int) $data['user_id'] : null,
            subscription_id: isset($data['subscription_id']) ? (int) $data['subscription_id'] : null,
            used_properties: isset($data['used_properties']) ? (int) $data['used_properties'] : null,
            started_at: $data['started_at'] ?? null,
            expires_at: $data['expires_at'] ?? null,
            active: isset($data['active']) ? (bool) $data['active'] : null,
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'subscription_id' => $this->subscription_id,
            'used_properties' => $this->used_properties,
            'started_at' => $this->started_at,
            'expires_at' => $this->expires_at,
            'active' => $this->active,
        ], fn($value) => $value !== null);
    }

    /**
     * Convert DTO to array for create operation
     */
    public function toCreateArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'subscription_id' => $this->subscription_id,
            'used_properties' => $this->used_properties ?? 0,
            'started_at' => $this->started_at ?? now(),
            'expires_at' => $this->expires_at,
            'active' => $this->active ?? true,
        ];
    }

    /**
     * Convert DTO to array for update operation
     */
    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->used_properties !== null) $data['used_properties'] = $this->used_properties;
        if ($this->expires_at !== null) $data['expires_at'] = $this->expires_at;
        if ($this->active !== null) $data['active'] = $this->active;
        
        return $data;
    }
}
