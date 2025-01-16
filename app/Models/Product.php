<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const HIDDEN = 'hidden';
    public const SALE = 'sale';
    public const OUT = 'out';
    public const DELETED = 'deleted';
    public const PENDING = 'pending';

    public const STATUS = [
        self::HIDDEN,
        self::SALE,
        self::OUT,
        self::DELETED,
        self::PENDING
    ];

    protected $fillable = [
        'id',
        'name',
        'image',
        'sku',
        'status',
        'price',
        'currency',
        'quantity',
        'delete_reason'
    ];

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function productQuantity(): Attribute
    {
        return new Attribute(
            get: function (): array {
                if($this->variations->count() > 0) {
                    return $this->variations()->pluck('quantity')->toArray();
                }
                return [$this->quantity];
            },
        );
    }
}
