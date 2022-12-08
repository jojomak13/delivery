<?php

namespace App\Http\Resources\Seller\Bundle;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
        return [
            'id' => Str::random(10),
            'product' => [
                'id' => $this->id,
                'name' => $this->name,
                'size' => $this->size,
            ],
            'size' => $this->pivot->size
        ];
    }
}
