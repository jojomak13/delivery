<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const TYPES = [
        'product'   => Product::class,
        'bundle'    => Bundle::class,
        'extra'     => Extra::class,
    ];

    protected $casts = [
        'options' => 'json'
    ];

    /**
     * @return MorphTo
     */
    public function cartable(): MorphTo
    {
        return $this->morphTo();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
