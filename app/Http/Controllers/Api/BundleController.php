<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BundlesResource;
use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
        ]);

        return BundlesResource::collection(
            Bundle::query()
                ->select('bundles.*', 'branches.location', 'branches.delivery_distance', 'branches.id as branch_id')
                ->join('stores', 'bundles.store_id', '=', 'stores.id')
                ->join('branches', 'branches.store_id', '=', 'stores.id')
                ->where('approved', true)
                ->latest()
                ->get()
                ->filter(function($bundle){
                    $location = json_decode($bundle->location);
                    return distance($location->lat, $location->long, request()->input('lat'), request()->input('long')) <= $bundle->delivery_distance;  
                })
        )->paginate(15);   
    }

    public function show(Bundle $bundle)
    {
        return response()->json($bundle->load('products'));
    }
}
