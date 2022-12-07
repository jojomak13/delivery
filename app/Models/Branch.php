<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
