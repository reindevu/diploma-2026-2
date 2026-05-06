<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::withCount('orders')->withSum('orders', 'total')->orderBy('id')->paginate(20),
            'meta' => ['title' => 'Пользователи | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }
}
