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
use Illuminate\Validation\ValidationException;

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
            ->whereJsonContains('options', request()->input('options'))
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

    public function updateQuantity($cartItem, $quantity)
    {
        if($cartItem->quantity + $quantity <= 0){
            throw ValidationException::withMessages([
                'quantity' => __('app.cart.quantity_not_valid'),
            ]);
        }
            
        $cartItem->update([
            'quantity' => $cartItem->quantity + $quantity
        ]);
    }

    public function update(Cart $cart, Request $request)
    {
        abort_unless($cart->user_id === $request->user()->id, 403);

        $data = $request->validate(['quantity' => 'required|integer|min:1']);

        if($cart->quantity + $data['quantity'] <= 0){
            throw ValidationException::withMessages([
                'quantity' => __('app.cart.quantity_not_valid'),
            ]);
        }
            
        $cart->update(['quantity' => $data['quantity']]);

        return new CartResource($request->user()->cart->load('cartable'));
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

