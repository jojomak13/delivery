<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Type extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['image_url'];

    const TYPE_PRODUCT = 'product';
    const TYPE_STORE = 'store';

    /**
     * @return HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withTimestamps();
    }

    public function updateImage($image)
    {
        Storage::disk('public')->delete($this->image ?? '');

        $this->image = $image->store('types', 'public');

        $this->save();
    }

    protected function imageUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image? url('storage/' . $this->image) : url('images/category.png')
        );
    }
}
