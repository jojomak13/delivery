<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Store\CreateStoreRequest;
use App\Http\Requests\Seller\Store\UpdateStoreRequest;
use App\Models\Store;
use App\Models\Type;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function index()
    {
        $store = Store::where('seller_id', auth('seller')->id())->with('branches')->first();

        $categories = auth('seller')->user()->myStore->categories()->paginate();

        return Inertia::render('Seller/Store/Index', [
            'store' => $store,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        if(auth('seller')->user()->myStore)
            return abort(403);

        $types = Type::select('id', 'name')->where('type', Type::TYPE_STORE)->latest()->get();

        return Inertia::render('Seller/Store/Create', [
            'types' => $types
        ]);
    }

    public function store(CreateStoreRequest $request)
    {
        $store = Store::create([
            'seller_id' => auth('seller')->id(),
            'work_time' => [
                'from' => $request->input('from'),
                'to' => $request->input('to'),
            ],
        ] + $request->validated());

        $store->updateImage($request->file('logo'));

        return redirect()->route('seller.store.index');
    }

    public function edit()
    {
        $store = auth('seller')->user()->myStore;

        if(!$store)
            return abort(4003);


        $types = Type::select('id', 'name')->latest()->get();

        return Inertia::render('Seller/Store/Edit', [
            'store' => $store,
            'types' => $types
        ]);
    }

    public function update(UpdateStoreRequest $request)
    {
        $store = auth('seller')->user()->myStore;

        $data = $request->validated();

        $store->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'work_time' => [
                'from' => $data['from'],
                'to' => $data['to']
            ],
        ]);

        if($request->hasFile('logo'))
            $store->updateImage($request->file('logo'));

        return redirect()->route('seller.store.index');
    }
}
