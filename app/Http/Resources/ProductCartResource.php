<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $price = $this->cartable->price;

        if($this->cartable_type === Product::class){
            $selectedSize = collect($this->cartable->size)->filter(fn ($el) => $el['name'] === $this->options['size'])->first();
            $price = (float) $selectedSize['price'];
        }

        return [
            'id' => $this->id,
            'name' => $this->cartable->name,
            'image' => $this->cartable->image_url,
            'cartable_type' => $this->cartable_type,
            'quantity' => $this->quantity,
            'price' => $price,
            'total_price' => $price * $this->quantity,
        ];
    }
}
