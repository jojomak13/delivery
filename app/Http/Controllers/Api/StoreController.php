<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoriesResource;
use App\Http\Resources\Api\ProductsResource;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Store $store, Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
        ]);

        $store->load(['categories', 'branches']);

        $branches = $store->branches->filter(function($store){
            return distance($store->location['lat'], $store->location['long'], request()->input('lat'), request()->input('long')) <= $store->delivery_distance;
        });

        abort_unless($branches->count(), 404);

        $branch = $branches->first();
        
        return response()->json([
            'id' => $store->id,
            'name' => $store->name,
            'description' => $store->description,
            'logo' => $store->logo_url,
            'work_time' => $store->work_time,
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name 
            ],
            'categories' => CategoriesResource::collection($store->categories),
            'products' => ProductsResource::collection(Product::query()
                ->with('types')
                ->where('store_id', $store->id)
                // TODO:: uncomment this
//                ->where('approved', true)
                ->where('available', true)
                ->get())
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['extras', 'types']);

        return response()->json($product);
    }
}
