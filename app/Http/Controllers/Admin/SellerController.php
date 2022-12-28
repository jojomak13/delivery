<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sellers\CreateSellerRequest;
use App\Models\Seller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::query()->with('myStore:id,name,seller_id')->latest()->paginate(10);

        return Inertia::render('Admin/Sellers/Index', [
            'sellers' => $sellers
        ]);
    }

    public function edit(Seller $seller)
    {
        return Inertia::render('Admin/Sellers/Edit', [
            'seller' => $seller
        ]);
    }

    public function update(CreateSellerRequest $request, Seller $seller)
    {
        $seller->update($request->validated());

        return redirect()->route('admin.sellers.index');
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return redirect()->route('admin.sellers.index');
    }
}
