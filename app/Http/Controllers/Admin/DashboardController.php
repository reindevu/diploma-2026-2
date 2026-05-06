<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.index', [
            'productsCount' => Product::count(),
            'ordersCount' => Order::count(),
            'usersCount' => User::where('role', 'user')->count(),
            'categoriesCount' => Category::whereIn('slug', ['coffee', 'desserts'])->count(),
            'meta' => ['title' => 'Панель администратора | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }
}
