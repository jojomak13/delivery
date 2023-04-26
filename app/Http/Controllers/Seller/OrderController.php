<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = auth('seller')->user()->myStore;

        $orders = Order::query()
            ->with(['user:id,name', 'branch:id,name'])
            ->where('store_id', $store->id)
            ->when($request->has('branch_id'), function($q) use($request) {
                $q->where('branch_id', $request->input('branch_id'));
            })
            ->latest()
            ->paginate(15);

        return Inertia::render('Seller/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        $store = auth('seller')->user()->myStore;

        abort_unless($order->store_id === $store->id, 404);

        $order->load(['user:id,name']);

        return Inertia::render('Seller/Orders/Show', [
            'order' => $order,
        ]);
    }

    public function update(Order $order, Request $request)
    {
        $store = auth('seller')->user()->myStore;

        abort_unless($order->store_id === $store->id, 404);

        $order->update(['status' => $request->input('status')]);

        Notification::sendNow([$order->user], new OrderNotification($order));
    }
}
