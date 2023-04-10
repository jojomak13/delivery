<?php

namespace App\Http\Resources\Api;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoritesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->favorable_type === Favorite::TYPES['store'])
            return [
                'id' => $this->id,
                'store_id' => $this->favorable->id,
                'branch' => [
                    'id' => $this->branch_id,
                    'delivery_distance' => $this->branch->delivery_distance . ' ' . __('app.minutes'),
                ],
                'name' => $this->favorable->name,
                'description' => $this->favorable->description,
                'image' => $this->favorable->logo_url,
            ];
            else
            return [
                'id' => $this->id,
                'product_id' => $this->favorable->id,
                'name' => $this->favorable->name,
                'description' => $this->favorable->description,
                'size' => $this->favorable->size,
                'image' => $this->favorable->image_url,
                'category_id' => $this->favorable->category_id,
                'branch_id' => $this->branch_id, 
            ];
    }
}
