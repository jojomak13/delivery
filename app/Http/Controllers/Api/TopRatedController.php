<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StoreResource;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopRatedController extends Controller
{
    public function __invoke(Request $request)
    {
        $storeIds =  DB::table('orders')
            ->select('store_id', DB::raw('count(id) as freq'))
            ->groupBy('store_id')
            ->orderBy('freq', 'desc')
            ->take(20)
            ->pluck('store_id'); 

            $request->validate([
                'lat' => 'required',
                'long' => 'required',
            ]);
    
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
            }));
    }
}
