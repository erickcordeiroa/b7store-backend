<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_code',
        'total_amount',
        'status',
        'shipping_cost',
        'shipping_days',
        'shipping_zipcode',
        'shipping_street',
        'shipping_number',
        'shipping_complement',
        'shipping_city',
        'shipping_state',
        'shipping_country',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'shipping_days' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function generateTrackingCode(): string
    {
        return 'order_' . uniqid();
    }
}

