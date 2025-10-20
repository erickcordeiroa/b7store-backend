<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_code',
        'status',
        'total_amount',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
