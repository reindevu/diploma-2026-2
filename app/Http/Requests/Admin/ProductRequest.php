<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = is_object($product) ? $product->id : null;

        return [
            'category_id' => ['required', Rule::exists('categories', 'id')->where(fn ($query) => $query->whereIn('slug', ['coffee', 'desserts']))],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'description' => ['required', 'string'],
            'image' => [$productId ? 'nullable' : 'required', 'image', 'max:4096'],
            'active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'variants.*.name' => ['required', 'string', 'max:255'],
            'variants.*.measure_value' => ['nullable', 'integer', 'min:0'],
            'variants.*.measure_unit' => ['nullable', 'string', 'max:20'],
            'variants.*.price' => ['required', 'integer', 'min:1'],
            'variants.*.active' => ['nullable', 'boolean'],
            'variants.*.sort_order' => ['nullable', 'integer'],
        ];
    }
}
