<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Branch\CreateBranchRequest;
use App\Http\Requests\Seller\Branch\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Seller/Branches/Create');
    }

    /**
     * @param CreateBranchRequest $request
     * @return RedirectResponse
     */
    public function store(CreateBranchRequest $request): RedirectResponse
    {
        $store = auth('seller')->user()->myStore;

        Branch::create([
            'store_id' => $store->id
        ] + $request->validated());

        return redirect()->route('seller.store.index');
    }

    /**
     * @param Branch $branch
     * @return Response
     */
    public function edit(Branch $branch): Response
    {
        if($branch->store->id !== auth('seller')->user()->myStore->id){
            return abort(404);
        }

        return Inertia::render('Seller/Branches/Edit', [
            'branch' => $branch
        ]);
    }

    /**
     * @param UpdateBranchRequest $request
     * @param Branch $branch
     * @return RedirectResponse
     */
    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $branch->update($request->validated());

        return redirect()->route('seller.store.index');
    }

    /**
     * @param Branch $branch
     * @return RedirectResponse
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        if($branch->store->id !== auth('seller')->user()->myStore->id){
            return abort(404);
        }

        $branch->delete();

        return redirect()->route('seller.store.index');
    }
}
