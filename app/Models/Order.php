<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts= [
        'location' => 'json',
        'items' => 'json'
    ];

    const PENDING = 'pending';
    const INPROGRESS = 'in-progress';
    const DELIVERED = 'delivered';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
