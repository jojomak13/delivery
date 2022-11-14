<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $categories = [];

        if($request->has('search')){
            $categories = Category::query()
                ->select('id', 'name')
                ->where('name', 'like', '%' . $request->input('search') . '%')
                ->get();
        }

        return Inertia::render('Seller/Categories/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required']);

        $store = auth('seller')->user()->myStore;

        if(is_array($data['name'])){
            $store->categories()->syncWithoutDetaching($data['name']['id']);
        } else {
            $category = Category::create(['name' => $data['name']]);
            $store->categories()->syncWithoutDetaching($category->id);
        }

        return redirect()->route('seller.store.index');
    }

}
