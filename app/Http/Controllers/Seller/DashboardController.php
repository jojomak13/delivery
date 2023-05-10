<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $store = $request->user()->myStore;

        $stats =  [
            [
                'name' => 'app.stats.promo_codes_count', 
                'initials' => 'PCC', 
                'count' => PromoCode::query()
                    ->where('store_id', $store?->id)
                    ->where('status', PromoCode::AVAILABLE)
                    ->count(), 
                'bgColor' => 'bg-pink-600'
            ],
            [
                'name' => 'app.stats.products_count', 
                'initials' => 'PC', 
                'count' => Product::where('store_id', $store?->id)->count(), 
                'bgColor' => 'bg-purple-600'
            ],
            [
                'name' => 'app.stats.bundles_count', 
                'initials' => 'BC', 
                'count' => Bundle::where('store_id', $store?->id)->count(), 
                'bgColor' => 'bg-yellow-500'
            ],
            [
                'name' => 'app.stats.orders_count', 
                'initials' => 'OC', 
                'count' => Order::query()
                    ->where('store_id', $store?->id)
                    ->where('status', Order::PENDING)
                    ->count(), 
                'bgColor' => 'bg-green-500'
            ],
        ];

        return Inertia::render('Dashboard', [
            'stats' => $store? $stats : [],
        ]);
    }
}
