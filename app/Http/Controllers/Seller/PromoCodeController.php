<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class PromoCodeController extends Controller
{
    public function index()
    {
        $store = auth('seller')->user()->myStore;
        $codes = PromoCode::where('store_id', $store->id)->latest()->paginate(10);

        return Inertia::render('Seller/Codes/Index', [
            'codes' => $codes
        ]);
    }

    public function create()
    {
        return Inertia::render('Seller/Codes/Create');
    }

    public function store(Request $request)
    {
        $store = auth('seller')->user()->myStore;

        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('promo_codes', 'name')->where('store_id', $store->id)],
            'limit' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:1',
            'end_at' => 'required|date',
        ]);

        PromoCode::create([
            'store_id' => $store->id
        ] + $data);
        
        
        return redirect()->route('seller.codes.index');
    }

    public function destroy(PromoCode $code)
    {
        $store = auth('seller')->user()->myStore;

        abort_if($code->store_id !== $store->id, 404);

        $code->delete();

        return redirect()->route('seller.codes.index');
    }
}
