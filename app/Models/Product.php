<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['image_url'];

    protected $casts = [
        'size' => 'collection'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany
     */
    public function extras(): BelongsToMany
    {
        return $this->belongsToMany(Extra::class)
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class)
            ->withTimestamps();
    }

    public function updateImage($image)
    {
        Storage::disk('public')->delete($this->image ?? '');

        $this->image = $image->store('products', 'public');

        $this->save();
    }

    public function getImageUrlAttribute(): string
    {
        return url('storage/' . $this->image);
    }
}
