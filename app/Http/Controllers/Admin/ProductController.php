<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with(['category', 'variants'])->orderBy('sort_order')->paginate(20),
            'meta' => ['title' => 'Товары | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['active' => true, 'sort_order' => 0]),
            'categories' => $this->menuCategories(),
            'meta' => ['title' => 'Создание товара | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');
        $data['image'] = $request->file('image')->store('uploads/products', 'public');
        $variants = $data['variants'];
        unset($data['variants']);

        $product = Product::create($data);
        $this->syncVariants($product, $variants);

        return redirect()->route('admin.products.edit', $product)->with('status', 'Товар создан.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load('variants'),
            'categories' => $this->menuCategories(),
            'meta' => ['title' => 'Редактирование товара | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');
        $variants = $data['variants'];
        unset($data['variants']);

        if ($request->hasFile('image')) {
            if (str_starts_with((string) $product->image, 'uploads/')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('uploads/products', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);
        $this->syncVariants($product, $variants);

        return back()->with('status', 'Товар обновлен.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if (str_starts_with((string) $product->image, 'uploads/')) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Товар удален.');
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $keep = [];

        foreach ($variants as $variant) {
            $payload = [
                'name' => $variant['name'],
                'measure_value' => $variant['measure_value'] ?? null,
                'measure_unit' => $variant['measure_unit'] ?? null,
                'price' => $variant['price'],
                'active' => isset($variant['active']),
                'sort_order' => $variant['sort_order'] ?? 0,
            ];

            $model = $product->variants()->updateOrCreate(['id' => $variant['id'] ?? null], $payload);
            $keep[] = $model->id;
        }

        $product->variants()->whereNotIn('id', $keep)->delete();
    }

    private function menuCategories()
    {
        return Category::whereIn('slug', ['coffee', 'desserts'])
            ->orderBy('sort_order')
            ->get();
    }
}
