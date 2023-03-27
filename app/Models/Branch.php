<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'location' => 'json'
    ];

    protected $attributes = [
        'location' => '["lat": "", "long": ""]'
    ];

    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrders() : HasMany
    {
        return $this->hasMany(Order::class)
            ->whereNotIn('status', [Order::COMPLETED, Order::CANCELED]);
    }
}
