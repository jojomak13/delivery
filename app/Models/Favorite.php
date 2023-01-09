<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const TYPES = [
        'product'   => Product::class,
        'bundle'    => Bundle::class,
        'store'     => Store::class,
    ];

    /**
     * @return MorphTo
     */
    public function favorable(): MorphTo
    {
        return $this->morphTo();
    }
}
