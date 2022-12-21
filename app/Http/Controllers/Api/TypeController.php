<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StoresResource;
use App\Http\Resources\Api\TypesResource;
use App\Models\Store;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function show(Type $type, Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required'
        ]);

        return new StoresResource(Store::query()
                ->where('type_id', $type->id)
                ->where('approved', true)
                ->select('stores.*', 'sellers.approved')
                ->join('sellers', 'sellers.id', '=', 'stores.seller_id')
                ->with('branches')
                ->simplePaginate(10));
    }
}
