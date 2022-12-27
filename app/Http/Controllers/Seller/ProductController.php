<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\CreateProductRequest;
use App\Http\Requests\Seller\Product\UpdateProductRequest;
use App\Models\Extra;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $store = auth('seller')->user()->myStore;

        $categories = $store->categories()->select('categories.id', 'name')->get();
        $extras = Extra::where('store_id', $store->id)->select('id', 'name')->get();
        $types = Type::select('id', 'name')->where('type', Type::TYPE_PRODUCT)->latest()->get();

        return Inertia::render('Seller/Products/Create', [
            'categories' => $categories,
            'extras' => $extras,
            'types' => $types
        ]);
    }

    public function store(CreateProductRequest $request)
    {
        $store = auth('seller')->user()->myStore;

        $product = $store->products()->create($request->validated());

        $product->updateImage($request->file('image'));

        $product->extras()->sync($request->input('extra'));
        $product->types()->sync($request->input('types'));

        return redirect()->route('seller.products.index');
    }

    public function edit(Product $product)
    {
        if(!auth('seller')->user()->myStore->products()->find($product->id)){
            return abort(404);
        }

        $product->load('extras:id', 'types:id');

        $store = auth('seller')->user()->myStore;

        $categories = $store->categories()->select('categories.id', 'name')->get();
        $extras = Extra::where('store_id', $store->id)->select('id', 'name')->get();
        $types = Type::select('id', 'name')->where('type', Type::TYPE_PRODUCT)->latest()->get();

        return Inertia::render('Seller/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'extras' => $extras,
            'types' => $types
        ]);
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $data = $request->validated();

        unset($data['image']);

        $product->update($data);

        $product->extras()->sync($request->input('extra'));
        $product->types()->sync($request->input('types'));

        if($request->hasFile('image'))
            $product->updateImage($request->file('image'));

        return redirect()->route('seller.products.index');
    }

    public function destroy(Product $product)
    {
        if(!auth('seller')->user()->myStore->products()->find($product->id)){
            return abort(404);
        }

        Storage::disk('public')->delete($product->image);

        $product->delete();

        return redirect()->route('seller.products.index');
    }
}
