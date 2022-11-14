<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['image_url'];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function updateImage($image)
    {
        Storage::disk('public')->delete($this->image);

        $this->image = $image->store('products', 'public');
        
        $this->save();
    }

    public function getImageUrlAttribute(): string
    {
        return url('storage/' . $this->image);
    }
}
