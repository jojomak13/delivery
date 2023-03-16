<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\ProductCartResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $items = ProductCartResource::collection($this)->toArray($request);
        $subTotal = collect($items)->sum(fn ($el) => $el['total_price']);
        $branch = $this->first()?->branch;

        return [
            'items' => $items,
            'sub_total' => $subTotal,
            'delivery_price' => (float) $branch?->delivery_cost,
            'total_price' => $subTotal + (float) $branch?->delivery_cost,
            'estimated_time' => $branch?->delivery_period . ' ' . __('app.minutes'),
        ];
    }
}
