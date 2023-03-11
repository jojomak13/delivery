<?php

namespace App\Http\Resources\Api;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch_name,
            ],
            'delivery_period' => $this->delivery_period,
            'delivery_cost' => $this->delivery_cost,
            'location' => $this->location,
            'logo' => url('storage/' . $this->logo),
            'favorite_id' => $user? $user->favorites()
                ->where('favorable_type', Favorite::TYPES['store'])
                ->where('favorable_id', $this->store_id)
                ->first()?->id : null
        ];
    }
}
