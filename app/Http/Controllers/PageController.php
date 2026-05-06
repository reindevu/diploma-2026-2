<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'meta' => [
                'title' => 'Кофейня CoffeeDoo — авторский кофе и десерты',
                'description' => 'Кофейня CoffeeDoo — уютное место с авторским кофе и домашними десертами. Свежая обжарка, атмосфера и забота о каждом госте.',
                'image' => asset('image/index.png'),
                'robots' => 'index, follow',
                'jsonLd' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'CafeOrCoffeeShop',
                    'name' => 'CoffeeDoo',
                    'image' => asset('image/index.png'),
                    'address' => 'проспект Мира, 40, Чебоксары, Россия',
                    'telephone' => '+77777777777',
                ],
            ],
        ]);
    }

    public function menu(string $slug): View
    {
        $category = Category::where('slug', $slug)->where('active', true)->firstOrFail();
        $products = Product::with('activeVariants')
            ->whereBelongsTo($category)
            ->where('active', true)
            ->whereHas('variants', fn ($query) => $query->where('active', true))
            ->orderBy('sort_order')
            ->get();

        $isCoffee = $slug === 'coffee';
        $isDesserts = $slug === 'desserts';
        $title = match ($slug) {
            'coffee' => 'Меню кофе | Кофейня CoffeeDoo',
            'desserts' => 'Меню десертов | Кофейня CoffeeDoo',
            default => $category->name.' | Меню CoffeeDoo',
        };
        $description = match ($slug) {
            'coffee' => 'Меню кофе в кофейне CoffeeDoo: капучино, латте, эспрессо, американо и авторские напитки.',
            'desserts' => 'Десерты в кофейне CoffeeDoo: чизкейк, тирамису, брауни, эклеры, фондан и другие вкусности к кофе.',
            default => $category->description ?: 'Меню категории '.$category->name.' в кофейне CoffeeDoo.',
        };

        return view('pages.menu', [
            'category' => $category,
            'products' => $products,
            'meta' => [
                'title' => $title,
                'description' => $description,
                'image' => asset($isCoffee ? 'image/coffee-menu/image 4.png' : ($isDesserts ? 'image/dessert-menu/image.png' : 'image/logo.png')),
                'robots' => 'index, follow',
            ],
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::with(['category', 'activeVariants'])
            ->where('slug', $slug)
            ->where('active', true)
            ->whereHas('variants', fn ($query) => $query->where('active', true))
            ->firstOrFail();

        $minPrice = $product->activeVariants->min('price');

        return view('products.show', [
            'product' => $product,
            'variant' => $product->activeVariants->first(),
            'meta' => [
                'title' => $product->seo_title ?: $product->name.' | CoffeeDoo',
                'description' => $product->seo_description ?: $product->description,
                'image' => $product->imageUrl(),
                'robots' => 'index, follow',
                'jsonLd' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => $product->name,
                    'description' => $product->description,
                    'image' => $product->imageUrl(),
                    'offers' => [
                        '@type' => 'Offer',
                        'priceCurrency' => 'RUB',
                        'price' => $minPrice,
                        'availability' => 'https://schema.org/InStock',
                    ],
                ],
            ],
        ]);
    }

    public function contacts(): View
    {
        return view('pages.contacts', [
            'meta' => [
                'title' => 'Контакты кофейни CoffeeDoo | Адрес, телефон, часы работы',
                'description' => 'Контакты кофейни CoffeeDoo: адрес, телефон, часы работы, как добраться. Ждем вас в гости!',
                'robots' => 'index, follow',
                'jsonLd' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'CafeOrCoffeeShop',
                    'name' => 'CoffeeDoo',
                    'address' => 'проспект Мира, 40, Чебоксары, Россия',
                    'openingHours' => 'Mo-Su 08:00-22:00',
                    'telephone' => '+77777777777',
                ],
            ],
        ]);
    }
}
