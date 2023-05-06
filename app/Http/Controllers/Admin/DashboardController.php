<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $stats =  [
            [
                'name' => 'app.stats.stores_count', 
                'initials' => 'SC', 
                'count' => Store::whereHas('seller', function($q){
                    $q->where('approved', false);
                })->count(), 
                'bgColor' => 'bg-pink-600'
            ],
            [
                'name' => 'app.stats.products_count', 
                'initials' => 'PC', 
                'count' => Product::where('approved', false)->count(), 
                'bgColor' => 'bg-purple-600'
            ],
            [
                'name' => 'app.stats.bundles_count', 
                'initials' => 'BC', 
                'count' => Bundle::where('approved', false)->count(), 
                'bgColor' => 'bg-yellow-500'
            ],
            [
                'name' => 'app.stats.orders_count', 
                'initials' => 'OC', 
                'count' => Order::where('status', Order::PENDING)->count(), 
                'bgColor' => 'bg-green-500'
            ],
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }
}
