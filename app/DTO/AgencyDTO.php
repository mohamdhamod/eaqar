<?php

namespace App\DTO;

class AgencyDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $name = null,
        public ?string $slug = null,
        public ?string $logo = null,
        public ?string $address = null,
        public ?bool $isActive = false,
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'] ?? null,
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            logo: $data['logo'] ?? null,
            address: $data['address'] ?? null,
            isActive: $data['is_active'] ?? false,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'address' => $this->address,
            'is_active' => $this->isActive,
        ];
    }

    /**
     * Get data for creation (excludes ID and timestamps).
     */
    public function toCreateArray(): array
    {
        return array_filter([
            'user_id' => $this->userId,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'address' => $this->address,
            'is_active' => $this->isActive,
        ], fn($value) => $value !== null);
    }

    /**
     * Get data for update (excludes ID, user_id, and timestamps).
     */
    public function toUpdateArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'address' => $this->address,
            'is_active' => $this->isActive,
        ], fn($value) => $value !== null);
    }
}
