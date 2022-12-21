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
        $branches = $this->branches->filter(function ($branch){
            return distance($branch->location['lat'], $branch->location['long'], request()->input('lat'), request()->input('long')) <= $branch->delivery_distance;
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo_url,
            'branch' => $branches->count()? $branches[0] : null
        ];
    }
}
