<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\CreateProductRequest;
use App\Http\Requests\Seller\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = auth('seller')
            ->user()
            ->myStore
            ->products()
            ->when($request->query('category'), function($q, $category){
                $q->where('category_id', $category);
            })
            ->with('category:id,name')
            ->latest()
            ->paginate(10);

        return Inertia::render('Seller/Products/Index', [
            'products' => $products
        ]);
    }
    
    public function create()
    {
        $categories = auth('seller')->user()->myStore->categories()->select('categories.id', 'name')->get();

        return Inertia::render('Seller/Products/Create', [
            'categories' => $categories
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        $store = auth('seller')->user()->myStore;
        
        $product = $store->products()->create($request->validated());

        $product->updateImage($request->file('image'));

        return redirect()->route('seller.products.index');
    }

    public function edit(Product $product)
    {
        if(!auth('seller')->user()->myStore->products()->find($product->id)){
            return abort(404);
        }

        $categories = auth('seller')->user()->myStore->categories()->select('categories.id', 'name')->get();

        return Inertia::render('Seller/Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $data = $request->validated();

        unset($data['image']);

        $product->update($data);

        if($request->hasFile('image'))
            $product->updateImage($request->file('image'));

        return redirect()->route('seller.products.index');
    }
    
    public function destroy(Product $product)
    {
        if(!auth('seller')->user()->myStore->products()->find($product->id)){
            return abort(404);
        }
        
        $product->delete();
        
        return redirect()->route('seller.products.index');
    }
}
