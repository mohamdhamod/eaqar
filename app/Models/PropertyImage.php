<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    protected $fillable = [
        'property_id',
        'image',
        'is_main',
        'sort_order',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    protected $appends = ['image_path'];

    public function getImagePathAttribute(): string
    {
        return $this->image != null ? asset('storage/' . $this->image) : asset('images/img.png');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
