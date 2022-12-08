<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Bundle\CreateBundleRequest;
use App\Http\Requests\Seller\Bundle\UpdateBundleRequest;
use App\Models\Bundle;
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
    public function create(): Response
    {
        $store = auth('seller')->user()->myStore;

        $categories = $store->categories()->select('categories.id', 'name')->get();

        return Inertia::render('Seller/Bundles/Create', [
            'categories' => $categories
        ]);
    }

    public function store(CreateBundleRequest $request)
    {
        $store = auth('seller')->user()->myStore;

        $bundle = $store->bundles()->create($request->validated());

        $bundle->updateImage($request->file('image'));

        return redirect()->route('seller.bundles.index');
    }

    public function edit(Bundle $bundle)
    {
        abort_if(auth('seller')->user()->myStore->id !== $bundle->store_id, 404);

        $store = auth('seller')->user()->myStore;

        $categories = $store->categories()->select('categories.id', 'name')->get();

        return Inertia::render('Seller/Bundles/Edit', [
            'categories' => $categories,
            'bundle' => $bundle,
        ]);
    }

    public function update(UpdateBundleRequest $request, Bundle $bundle)
    {
        abort_if(auth('seller')->user()->myStore->id !== $bundle->store_id, 404);

        $data = $request->validated();

        unset($data['image']);

        $bundle = $bundle->update($data);

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
