<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StoreResource;
use App\Models\Branch;
use App\Models\Store;
use Illuminate\Http\Request;


class GlobalSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3|max:255'
        ]);

        $storeIds = Store::query()
            ->whereHas('categories', function($q){
                $q->where('name', 'like', '%' . request()->input('search') . '%');
            })
            ->pluck('id');

        return StoreResource::collection(Branch::query()
            ->select(['stores.id', 'stores.name', 'stores.logo', 'branches.id as branch_id', 'branches.name as branch_name', 'branches.delivery_cost', 'branches.delivery_period', 'branches.location', 'branches.store_id', 'branches.delivery_distance'])
            ->join('stores', 'stores.id', '=', 'branches.store_id')
            ->join('sellers', 'sellers.id', '=', 'stores.seller_id')
            ->whereIn('stores.id', $storeIds)
            // TODO:: un-comment it
//            ->where('approved', true)
            ->get()
            ->filter(function($store){
                return distance($store->location['lat'], $store->location['long'], request()->input('lat'), request()->input('long')) <= $store->delivery_distance;
            }))
            ->paginate(15);
    }
}
