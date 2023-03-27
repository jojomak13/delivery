<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Resources\Api\CartResource;
use App\Http\Resources\Api\OrdersResource;
use App\Models\Branch;
use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return OrdersResource::collection(Order::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15));
    }

    public function show(Order $order, Request $request)
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return $order;
    }

    public function store(CheckoutRequest $request)
    {
        abort_unless($request->user()->cart->count(), 400);

        $data = new CartResource($request->user()->cart->load('cartable')); 
        $data = $data->toArray($request); 
        $store = Branch::findOrFail($data['branch_id'])->store;

        if($request->has('code_id'))
            $this->validateCode($request->input('code_id'), $store);

        $order = Order::create([
            'items' => $data['items'],
            'sub_price' => $data['sub_total'],
            'delivery_price' => $data['delivery_price'],
            'discount_price' => $data['discount'],
            'total_price' => $data['total_price'],
            'estimated_time' => (int) $data['estimated_time'],
            'location' => $request->input('location'),
            'promo_code_id' => $request->input('code_id'),
            'user_id' => $request->user()->id,
            'branch_id' => $data['branch_id'],
            'store_id' => $store->id,
        ]);

        $request->user()->cart->toQuery()->delete();

        return response()->json([
            'status' => true,
            'msg' => __('app.order.created'),
            'data' => $order 
        ]);
    }

    private function validateCode($code_id, $store)
    {
        $code = PromoCode::findOrFail($code_id);
        
        if($code->end_at->lessThanOrEqualTo(now())){
            $code->update(['status' => PromoCode::EXPIRED]);
        }

        abort_unless($code->store_id === $store->id, 422);
        
        abort_if($code->status === PromoCode::FINISHED, 422, __('app.code.finished'));
        
        abort_if($code->status === PromoCode::EXPIRED, 422, __('app.code.expired')); 
        
        $data = ['current' =>  $code->current + 1];

        if($data['current'] == $code->limit)
            $data['status'] = PromoCode::FINISHED;

        $code->update($data);
    }
}
