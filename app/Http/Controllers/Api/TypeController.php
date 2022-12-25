<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StoresResource;
use App\Http\Resources\Api\TypesResource;
use App\Models\Branch;
use App\Models\Store;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class TypeController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $types = TypesResource::collection(Type::query()->latest()->get());

        return response()->json([
            'data' => $types,
        ]);
    }

    /**
     * @param $items
     * @param $perPage
     * @param $page
     * @param $options
     * @return LengthAwarePaginator
     */
    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function show(Type $type, Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
        ]);

        $stores = Branch::query()
            ->select(['stores.id', 'stores.name', 'stores.logo', 'branches.name as branch_name', 'branches.location', 'branches.location', 'branches.delivery_distance'])
            ->join('stores', 'stores.id', '=', 'branches.store_id')
            ->where('stores.type_id', $type->id)
            ->get()
            ->filter(function($store){
                return distance($store->location['lat'], $store->location['long'], request()->input('lat'), request()->input('long')) <= $store->delivery_distance;
            });

        return $this->paginate($stores, 15);

//        return $this->paginate($stores, 1);
        //        return new StoresResource(Store::query()
//                ->where('type_id', $type->id)
//                ->where('approved', true)
//                ->select('stores.*', 'sellers.approved')
//                ->join('sellers', 'sellers.id', '=', 'stores.seller_id')
//                ->with('branches')
//                ->simplePaginate(10));
    }
}
