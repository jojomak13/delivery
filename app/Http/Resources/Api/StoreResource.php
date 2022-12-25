<?php

namespace App\Http\Resources\Api;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'branch_name' => $this->branch_name,
            'work_time' => json_decode($this->work_time),
            'delivery_cost' => $this->delivery_cost,
            'location' => $this->location,
            'logo' => url('storage/' . $this->logo)
        ];
    }
}
