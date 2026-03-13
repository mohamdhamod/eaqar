<?php

namespace App\DTO;

class SubscriptionDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $key = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?float $price = null,
        public ?int $currency_id = null,
        public ?int $duration_days = null,
        public ?int $max_properties = null,
        public ?string $icon = null,
        public ?string $color = null,
        public ?int $sort_order = null,
        public ?bool $active = null,
    ) {}

    /**
     * Create DTO from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            key: $data['key'] ?? null,
            name: $data['name'] ?? null,
            description: $data['description'] ?? null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            currency_id: isset($data['currency_id']) ? (int) $data['currency_id'] : null,
            duration_days: isset($data['duration_days']) ? (int) $data['duration_days'] : null,
            max_properties: isset($data['max_properties']) ? (int) $data['max_properties'] : null,
            icon: $data['icon'] ?? null,
            color: $data['color'] ?? null,
            sort_order: isset($data['sort_order']) ? (int) $data['sort_order'] : null,
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
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency_id' => $this->currency_id,
            'duration_days' => $this->duration_days,
            'max_properties' => $this->max_properties,
            'icon' => $this->icon,
            'color' => $this->color,
            'sort_order' => $this->sort_order,
            'active' => $this->active,
        ], fn($value) => $value !== null);
    }

    /**
     * Convert DTO to array for create operation
     */
    public function toCreateArray(): array
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency_id' => $this->currency_id,
            'duration_days' => $this->duration_days,
            'max_properties' => $this->max_properties,
            'icon' => $this->icon,
            'color' => $this->color,
            'sort_order' => $this->sort_order ?? 0,
            'active' => $this->active ?? true,
            'slug' => str($this->key)->slug(),
        ];
    }

    /**
     * Convert DTO to array for update operation
     */
    public function toUpdateArray(): array
    {
        $data = [];
        if ($this->key !== null) $data['key'] = $this->key;
        if ($this->description !== null) $data['description'] = $this->description;
        if ($this->price !== null) $data['price'] = $this->price;
        if ($this->currency_id !== null) $data['currency_id'] = $this->currency_id;
        if ($this->duration_days !== null) $data['duration_days'] = $this->duration_days;
        if ($this->max_properties !== null) $data['max_properties'] = $this->max_properties;
        if ($this->icon !== null) $data['icon'] = $this->icon;
        if ($this->color !== null) $data['color'] = $this->color;
        if ($this->sort_order !== null) $data['sort_order'] = $this->sort_order;
        
        return $data;
    }
}
