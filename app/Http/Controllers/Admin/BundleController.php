<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBundleRequest;
use App\Http\Resources\Seller\Bundle\EditBundleResource;
use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Inertia\Inertia;

class BundleController extends Controller
{
    
    /**
     * @return Response
     */
    public function index(): Response
    {
        $bundles = Bundle::query()
            ->with(['category:id,name', 'store:id,name'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Bundles/Index', [
            'bundles' => $bundles
        ]);
    }

    public function edit(Request $request, Bundle $bundle)
    {
        $store = $bundle->store;

        $categories = $store->categories()->select('categories.id', 'name')->get();

        $products = [];

        if($request->has('search')){
            $products = Product::query()
                ->select('id', 'name', 'size')
                ->where('store_id', $store->id)
                ->where('name', 'like', '%' . $request->input('search') . '%')
                ->get();
        }

        return Inertia::render('Admin/Bundles/Edit', [
            'categories' => $categories,
            'bundle' => (new EditBundleResource($bundle))->toArray(null),
            'products' => $products
        ]);
    }

    public function update(UpdateBundleRequest $request, Bundle $bundle)
    {
        $data = $request->validated();

        unset($data['image']);

        $bundle->update($data);

        $bundle->products()->sync($request->input('products'));

        if($request->hasFile('image'))
            $bundle->updateImage($request->file('image'));

        return redirect()->route('admin.bundles.index');
    }

    public function destroy(Bundle $bundle)
    {
        Storage::disk('public')->delete($bundle->image);

        $bundle->delete();

        return redirect()->route('admin.bundles.index');
    }
}
