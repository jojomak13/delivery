<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ExtraController extends Controller
{
    public function index()
    {
        $store = auth('seller')->user()->myStore;
        $extras = Extra::where('store_id', $store->id)->latest()->paginate(10);

        return Inertia::render('Seller/Extras/Index', [
            'extras' => $extras
        ]);
    }

    public function create()
    {
        return Inertia::render('Seller/Extras/Create');
    }

    public function store(Request $request)
    {
        $store = auth('seller')->user()->myStore;

        $data = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'image' => 'required|image',
        ]);

        $extra = Extra::create([
            'store_id' => $store->id
        ] + $data);
        
        $extra->updateImage($data['image']);
        
        return redirect()->route('seller.extras.index');
    }

    public function edit(Extra $extra)
    {
        $store = auth('seller')->user()->myStore;

        abort_if($extra->store_id !== $store->id, 404);

        return Inertia::render('Seller/Extras/Edit', [
            'extra' => $extra
        ]);
    }

    public function update(Request $request, Extra $extra)
    {
        $store = auth('seller')->user()->myStore;

        abort_if($extra->store_id !== $store->id, 404);

        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'image' => 'nullable|image',
        ]);

        $extra->update($request->only(['name', 'price']));

        if($request->hasFile('image'))
            $extra->updateImage($request->file('image'));

        return redirect()->route('seller.extras.index');
    }

    public function destroy(Request $request, Extra $extra)
    {
        $store = auth('seller')->user()->myStore;

        abort_if($extra->store_id !== $store->id, 404);

        Storage::disk('public')->delete($extra->image);

        $extra->delete();

        return redirect()->route('seller.extras.index');
    }
}
