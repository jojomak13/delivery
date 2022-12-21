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
    public function index(Store $store)
    {
        $store->load(['categories']);

        return response()->json([
            'id' => $store->id,
            'name' => $store->name,
            'description' => $store->description,
            'logo' => $store->logo_url,
            'work_time' => $store->work_time,
            'categories' => CategoriesResource::collection($store->categories),
            'products' => ProductsResource::collection(Product::query()
                ->where('store_id', $store->id)
                ->where('approved', true)
                ->where('available', true)
                ->get())
        ]);
    }
}
