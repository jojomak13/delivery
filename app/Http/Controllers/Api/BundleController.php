<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BundlesResource;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class BundleController extends Controller
{
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

    public function index()
    {
        $bundles = BundlesResource::collection(Bundle::query()
            // TODO:: un-comment this
//            ->where('approved', true)
            ->latest()
            ->get());

        return $this->paginate($bundles, 15);
    }

    public function show(Bundle $bundle)
    {
        return response()->json($bundle->load('products'));
    }
}
