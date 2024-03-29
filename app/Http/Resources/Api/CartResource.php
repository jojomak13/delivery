<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Cart\ItemsResource;
use App\Models\PromoCode;
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
        $items = ItemsResource::collection($this)->toArray($request);
        $subTotal = collect($items)->sum(fn ($el) => $el['total_price']);
        $branch = $this->first()?->branch;

        if($branch){
            $code = PromoCode::query()
                ->where('id', $request->input('code_id'))
                ->where('store_id', $branch->store->id)
                ->first();
        }

        $total = $subTotal + (float) $branch?->delivery_cost;

        if(isset($code)) $total -= (float) ($code->amount > $total? $total : $code->amount);

        return [
            'items' => $items,
            'branch_id' => $this->when($this->resource->count(), function(){
                return $this->resource->first()->branch_id;
            }),
            'sub_total' => $subTotal,
            'delivery_price' => (float) $branch?->delivery_cost,
            'discount' => isset($code)? $code->amount : 0,
            'total_price' => $total,
            'estimated_time' => $branch?->delivery_period . ' ' . __('app.minutes'),
        ];
    }
}
