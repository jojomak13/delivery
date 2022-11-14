<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Branch\CreateBranchRequest;
use App\Http\Requests\Seller\Branch\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchController extends Controller
{
    public function create()
    {
        return Inertia::render('Seller/Branches/Create');
    }

    public function store(CreateBranchRequest $request)
    {
        $store = auth('seller')->user()->myStore;

        Branch::create([
            'store_id' => $store->id
        ] + $request->validated());

        return redirect()->route('seller.store.index');
    }

    public function edit(Branch $branch)
    {
        if($branch->store->id !== auth('seller')->user()->myStore->id){
            return abort(404);
        }

        return Inertia::render('Seller/Branches/Edit', [
            'branch' => $branch
        ]);
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()->route('seller.store.index');
    }

    public function destroy(Branch $branch)
    {
        if($branch->store->id !== auth('seller')->user()->myStore->id){
            return abort(404);
        }

        $branch->delete();

        return redirect()->route('seller.store.index');
    }
}
