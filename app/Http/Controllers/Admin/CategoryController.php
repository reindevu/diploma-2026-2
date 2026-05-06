<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')
                ->whereIn('slug', ['coffee', 'desserts'])
                ->orderBy('sort_order')
                ->get(),
            'meta' => ['title' => 'Категории | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        abort_unless(in_array($category->slug, ['coffee', 'desserts'], true), 404);

        $data = $request->validated();
        $data['active'] = $request->boolean('active');
        $category->update($data);

        return back()->with('status', 'Категория обновлена.');
    }
}
