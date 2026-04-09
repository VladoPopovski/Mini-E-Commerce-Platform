<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = \Illuminate\Support\Str::ulid();
            }
        });
    }

    protected $fillable = [
        'vendor_id', 'name', 'description', 'price', 'stock', 'image_url', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price'  => 'decimal:2',
            'stock'  => 'integer',
            'status' => ProductStatus::class,
        ];
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query): mixed
    {
        return $query->where('status', ProductStatus::ACTIVE);
    }

    public function scopeForVendor($query, Vendor $vendor): mixed
    {
        return $query->where('vendor_id', $vendor->id);
    }
}
