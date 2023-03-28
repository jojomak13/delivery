<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Extra;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with(['category:id,name', 'store:id,name'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('extras:id', 'types:id');

        $store = $product->store;

        $categories = $store->categories()->select('categories.id', 'name')->get();
        $extras = Extra::where('store_id', $store->id)->select('id', 'name')->get();
        $types = Type::select('id', 'name')->where('type', Type::TYPE_PRODUCT)->latest()->get();

        return Inertia::render('Admin/Products/Edit', [
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

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->image);

        $product->delete();

        return redirect()->route('admin.products.index');
    }
}
