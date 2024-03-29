<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['logo_url'];

    protected $casts = [
        'work_time' => 'json',
        'types' => 'json',
    ];

    protected $attributes = [
        'work_time' => '{"from": "", "to": ""]'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function extras(): HasMany
    {
        return $this->hasMany(Extra::class);
    }

    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function codes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function updateImage($image)
    {
        Storage::disk('public')->delete($this->logo ?? '');

        $this->logo = $image->store('stores', 'public');
        $this->save();
    }

    public function getLogoUrlAttribute(): string
    {
        return url('/storage/' . $this->logo);
    }
}
