<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Bundle\CreateBundleRequest;
use App\Http\Requests\Seller\Bundle\UpdateBundleRequest;
use App\Http\Resources\Seller\Bundle\EditBundleResource;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BundleController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $bundles = auth('seller')
            ->user()
            ->myStore
            ->bundles()
            ->with('category:id,name')
            ->latest()
            ->paginate(10);

        return Inertia::render('Seller/Bundles/Index', [
            'bundles' => $bundles
        ]);
    }

    /**
     * @return Response
     */
    public function create(Request $request): Response
    {
        $store = auth('seller')->user()->myStore;

        $categories = $store->categories()->select('categories.id', 'name')->get();

        $products = [];

        if($request->has('search')){
            $products = Product::query()
                ->select('id', 'name', 'size')
                ->where('store_id', $store->id)
                ->where('name', 'like', '%' . $request->input('search') . '%')
                ->get();
        }

        return Inertia::render('Seller/Bundles/Create', [
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function store(CreateBundleRequest $request)
    {
        $store = auth('seller')->user()->myStore;

        $bundle = $store->bundles()->create($request->validated());

        $bundle->products()->sync($request->input('products'));

        $bundle->updateImage($request->file('image'));

        return redirect()->route('seller.bundles.index');
    }

    public function edit(Request $request, Bundle $bundle)
    {
        abort_if(auth('seller')->user()->myStore->id !== $bundle->store_id, 404);

        $store = auth('seller')->user()->myStore;

        $categories = $store->categories()->select('categories.id', 'name')->get();

        $products = [];

        if($request->has('search')){
            $products = Product::query()
                ->select('id', 'name', 'size')
                ->where('store_id', $store->id)
                ->where('name', 'like', '%' . $request->input('search') . '%')
                ->get();
        }

        return Inertia::render('Seller/Bundles/Edit', [
            'categories' => $categories,
            'bundle' => (new EditBundleResource($bundle))->toArray(null),
            'products' => $products
        ]);
    }

    public function update(UpdateBundleRequest $request, Bundle $bundle)
    {
        abort_if(auth('seller')->user()->myStore->id !== $bundle->store_id, 404);

        $data = $request->validated();

        unset($data['image']);

        $bundle->update($data);

        $bundle->products()->sync($request->input('products'));

        if($request->hasFile('image'))
            $bundle->updateImage($request->file('image'));

        return redirect()->route('seller.bundles.index');
    }

    public function destroy(Bundle $bundle)
    {
        abort_if(auth('seller')->user()->myStore->id !== $bundle->store_id, 404);

        Storage::disk('public')->delete($bundle->image);

        $bundle->delete();

        return redirect()->route('seller.bundles.index');
    }
}
