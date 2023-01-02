<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return MorphTo
     */
    public function favorable(): MorphTo
    {
        return $this->morphTo();
    }
}
