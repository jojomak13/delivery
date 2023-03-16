<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Extra extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['image_url'];

    public function updateImage($image)
    {
        Storage::disk('public')->delete($this->image ?? '');

        $this->image = $image->store('extras', 'public');

        $this->save();
    }

    public function getImageUrlAttribute(): string
    {
        return url('storage/' . $this->image);
    }
}
