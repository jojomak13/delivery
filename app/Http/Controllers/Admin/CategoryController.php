<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categoreis\CreateCategoryRequest;
use App\Http\Requests\Admin\Categoreis\UpdateCategoryRequest;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $categories = Type::query()->latest()->paginate(10);

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * @param CreateCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CreateCategoryRequest $request)
    {
        $type = Type::create($request->validated());

        if($request->hasFile('image'))
            $type->updateImage($request->file('image'));

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Type $category
     * @return Response
     */
    public function edit(Type $category): Response
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category
        ]);
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Type $category
     * @return RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, Type $category): RedirectResponse
    {
        $data = $request->validated();

        unset($data['image']);

        $category->update($data);

        if($request->hasFile('image'))
            $category->updateImage($request->file('image'));

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Type $category
     * @return RedirectResponse
     */
    public function destroy(Type $category): RedirectResponse
    {
        if($category->stores()->count() || $category->products()->count())
            return abort(403, __('app.category.delete_failed'));

        $category->delete();

        return redirect()->route('admin.categories.index');
    }
}
