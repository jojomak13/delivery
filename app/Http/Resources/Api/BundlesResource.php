<?php

namespace App\Http\Resources\Api;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class BundlesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = auth('sanctum')->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image_url,
            'category_id' => $this->category_id,
            'is_favorite' => $user && $user->favorites()
                ->where('favorable_type', Favorite::TYPES['bundle'])
                ->where('favorable_id', $this->id)
                ->count()
        ];
    }
}
