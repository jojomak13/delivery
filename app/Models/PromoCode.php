<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = [
        'end_at'
    ];

    const AVAILABLE = 'available';
    const EXPIRED = 'expired';
    const FINISHED = 'finished';

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
