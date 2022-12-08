<?php

namespace App\Http\Resources\Seller\Bundle;

use Illuminate\Http\Resources\Json\JsonResource;

class EditBundleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'allowed_items' => $this->allowed_items,
            'available' => $this->available,
            'products' => BundleProductsResource::collection($this->products)
        ];
    }
}
