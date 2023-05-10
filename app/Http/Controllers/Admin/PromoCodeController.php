<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PromoCodeController extends Controller
{
    public function index()
    {
        $codes = PromoCode::with('store:id,name')->latest()->paginate(10);

        return Inertia::render('Admin/Codes/Index', [
            'codes' => $codes
        ]);
    }

    public function create()
    {
        $stores = Store::select('id', 'name')->latest()->get();

        return Inertia::render('Admin/Codes/Create', [
            'stores' => $stores,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('promo_codes', 'name')->whereIn('store_id', $request->input('stores'))],
            'limit' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:1',
            'end_at' => 'required|date',
            'stores' => 'required|array|min:1',
        ]);

        foreach($request->input('stores') as $storeId){
            PromoCode::create([
                'store_id' => $storeId
            ] + $data);
        }
        
        return redirect()->route('admin.codes.index');
    }

    public function destroy(PromoCode $code)
    {
        $code->delete();

        return redirect()->route('admin.codes.index');
    }
}
