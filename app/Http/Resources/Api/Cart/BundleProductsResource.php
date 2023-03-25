<?php

namespace App\Http\Resources\Api\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class BundleProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $selectedSize = collect($this->size)->filter(fn ($el) => $el['name'] === $this->pivot->size)->first();
        $price = (float) $selectedSize['price'];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image_url,
            'size' => $this->pivot->size,
            'price' => $price,
        ];
    }
}
