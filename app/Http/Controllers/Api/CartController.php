<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddCartRequest;
use App\Http\Resources\Api\CartResource;
use App\Models\Branch;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return new CartResource($request->user()->cart->load('cartable')); 
    }

    public function store(AddCartRequest $request)
    {
        $type = Cart::TYPES[$request->input('cartable_type')];
        
        $item = $type::findOrFail($request->cartable_id);

        abort_unless(Branch::find($request->branch_id)->store_id === $item->store_id, 422, __('app.cart.same_store_error'));

        abort_unless($this->hasSameStore($item), 422, __('app.cart.same_store_error'));

        abort_unless($this->hasSameBranch($item), 422, __('app.cart.same_branch_error'));

        abort_unless($this->hasValidOptions($item), 422, __('app.cart.invalid_options'));

        $cartItem = $request->user()->cart()
                ->where('cartable_id', $request->input('cartable_id'))
                ->where('cartable_type', $type)
                ->first();

        if($cartItem)
            $this->updateQuantity($cartItem, $request->input('quantity'));
        else 
            $this->add($request, $type);

        return response()->json([
            'msg' => __('app.cart.added')
        ]);
    }

    private function hasValidOptions($item)
    {
        if(get_class($item) === Product::class){
            return collect($item->size)->some(fn ($el) => $el['name'] == request()->options['size']);
        } else if (get_class($item) === Bundle::class) {
            $ids = $item->products()->pluck('products.id')->toArray();

            return collect(request()->options['products'])->every(fn ($el) => in_array($el, $ids));
        }

        return true;
    }

    private function hasSameStore($item)
    {
        if(!request()->user()->cart()->count()) return true;

        return request()->user()->cart()->first()->branch->store_id === $item->store_id;
    }

    private function hasSameBranch($item)
    {
        if(!request()->user()->cart()->count()) return true;

        return request()->user()->cart()->first()->branch_id == request()->branch_id;
    }

    private function updateQuantity($cartItem, $quantity)
    {
        $cartItem->update([
            'quantity' => $cartItem->quantity + $quantity
        ]);
    }

    private function add(AddCartRequest $request, $type)
    {
        $request->user()->cart()->create([
            'cartable_id' => $request->input('cartable_id'),
            'cartable_type' => $type,
            'quantity' => $request->input('quantity'),
            'branch_id' => $request->branch_id,
            'options' => $request->input('options'),
        ]);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return response()->json([
            'msg' => __('app.cart.deleted')
        ]);
    }
}

