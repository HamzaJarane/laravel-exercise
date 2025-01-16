<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    public const AVAILABLE = 'available';
    public const UNAVAILABLE = 'unavailable';

    public const AVAILABILITY = [
        self::AVAILABLE,
        self::UNAVAILABLE,
    ];

    protected $fillable = [
        'payload',
        'quantity',
        'availability',
    ];
    protected $casts = [
        'payload' => 'json'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productAvailability(): Attribute
    {
        return new Attribute(
            get: fn() => $this->quantity > 0 ? ProductVariation::AVAILABLE : ProductVariation::UNAVAILABLE
        );
    }
}
