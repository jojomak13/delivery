<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bundle extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['image_url'];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['size'])
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function updateImage($image)
    {
        Storage::disk('public')->delete($this->image ?? '');

        $this->image = $image->store('bundles', 'public');

        $this->save();
    }

    public function branches()
    {
        return $this->hasManyThrough(Branch::class, Store::class);
    }

    public function store()
    {
        $this->belongsTo(Store::class);
    }

    public function getImageUrlAttribute(): string
    {
        return url('storage/' . $this->image);
    }
}
