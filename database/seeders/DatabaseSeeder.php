<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'coffee' => [
                'name' => 'Кофе',
                'description' => 'Погрузитесь в мир кофейного блаженства! Мы отбираем лучшие зерна и обжариваем их с любовью, чтобы каждая чашка стала источником вдохновения. От классики до авторских рецептов — откройте для себя новые грани кофейного вкуса в нашей уютной атмосфере.',
                'sort_order' => 10,
            ],
            'desserts' => [
                'name' => 'Десерты',
                'description' => 'Десерт — это сладкая точка в конце трапезы, маленькое волшебство, способное превратить обычный день в праздник. Это искусство в миниатюре, где кондитеры создают изысканные шедевры, играя с текстурами, ароматами и цветами.',
                'sort_order' => 20,
            ],
        ];

        foreach ($categories as $slug => $data) {
            Category::updateOrCreate(['slug' => $slug], $data + ['active' => true]);
        }

        $products = [
            ['coffee', 'Американо', 'americano', 'Классическая элегантность в каждой капле. Гармоничное сочетание крепости эспрессо и мягкости воды, приглашение к спокойному размышлению и наслаждению моментом.', 'image/coffee-menu/image 4.png', '250мл', 250, 'мл', 300, 10],
            ['coffee', 'Айс Латте', 'ice-latte', 'Освежающий микс кофе, молока и льда. Взрыв вкуса и энергии, идеальный способ охладиться и зарядиться позитивом.', 'image/coffee-menu/image 8.png', '250мл', 250, 'мл', 400, 20],
            ['coffee', 'Латте', 'latte', 'Изысканное сочетание кофе и молока, холст для творчества бариста. Мягкий вкус и воздушная текстура подарят минуты спокойствия и вдохновения.', 'image/coffee-menu/image 5.png', '250мл', 250, 'мл', 300, 30],
            ['coffee', 'Капучино', 'cappuccino', 'Нежное объятие бархатистой молочной пены и бодрящего эспрессо. Вдохновение, укутанное в тепло и уют.', 'image/coffee-menu/image 3.png', '250мл', 250, 'мл', 350, 40],
            ['coffee', 'Мокко', 'mokko', 'Шоколадное искушение, в котором переплетаются ноты кофе, какао и сладкой неги. Источник радости и удовольствия, маленький праздник в каждой чашке.', 'image/coffee-menu/image 6.png', '250мл', 250, 'мл', 350, 50],
            ['coffee', 'Эспрессо', 'espresso', 'Пробуждающий заряд энергии, концентрированная суть кофейного духа, основа для бесконечных кофейных вариаций. Идеальный старт для покорения новых вершин.', 'image/coffee-menu/image 2.png', '150мл', 150, 'мл', 200, 60],
            ['desserts', 'Брауни', 'brauni', 'Шоколадное искушение, насыщенный вкус, влажная текстура. Маленький кусочек счастья, способный поднять настроение в любой момент.', 'image/dessert-menu/image-2.png', '200гр', 200, 'гр', 300, 10],
            ['desserts', 'Тирамису', 'tiramisu', 'Воздушное облако из сливочного сыра маскарпоне, пропитанное ароматом кофе и нежной сладостью печенья савоярди. Итальянская классика, шедевр, который тает во рту, оставляя незабываемое послевкусие.', 'image/dessert-menu/image.png', '200гр', 200, 'гр', 350, 20],
            ['desserts', 'Чизкейк', 'cheesecake', 'Бархатистая текстура и богатый сливочный вкус, воплощение нежности и изысканности. Десерт, который покоряет сердца своей простотой и совершенством.', 'image/dessert-menu/image-1.png', '200гр', 200, 'гр', 400, 30],
            ['desserts', 'Эклер', 'eclair', 'Заварное тесто, наполненное нежным кремом и покрытое глазурью. Классический десерт, который всегда радует своим вкусом и изящной формой.', 'image/dessert-menu/image-3.png', '200гр', 200, 'гр', 250, 40],
            ['desserts', 'Фон дан', 'fon-dan', 'Шоколадный вулкан с жидкой начинкой, извергающейся при первом прикосновении ложки. Истинное наслаждение для любителей шоколада.', 'image/dessert-menu/image-5.png', '200гр', 200, 'гр', 300, 50],
        ];

        foreach ($products as [$categorySlug, $name, $slug, $description, $image, $variantName, $measureValue, $measureUnit, $price, $sortOrder]) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => $description,
                    'image' => $image,
                    'active' => true,
                    'sort_order' => $sortOrder,
                    'seo_title' => $name.' | CoffeeDoo',
                    'seo_description' => $description,
                ]
            );

            $variant = $product->variants()->orderBy('sort_order')->orderBy('id')->first();
            $variantData = [
                'name' => $variantName,
                'measure_value' => $measureValue,
                'measure_unit' => $measureUnit,
                'price' => $price,
                'active' => true,
                'sort_order' => 10,
            ];

            $variant
                ? $variant->update($variantData)
                : $product->variants()->create($variantData);
        }

        $this->call(AdminSeeder::class);
    }
}
