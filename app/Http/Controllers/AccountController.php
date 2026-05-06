<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load([
            'cartItems.product',
            'cartItems.variant',
            'orders.items',
        ]);

        return view('account.index', [
            'user' => $user,
            'orders' => $user->orders()->with('items')->latest()->get(),
            'cartItems' => $user->cartItems,
            'summary' => $this->summaryData($request),
            'meta' => ['title' => 'Личный кабинет | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function orders(Request $request): View
    {
        return view('account.orders', [
            'orders' => $request->user()->orders()->with('items')->latest()->get(),
            'meta' => ['title' => 'История заказов | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    private function summaryData(Request $request): array
    {
        $items = $request->user()->cartItems()->with(['product', 'variant'])->get();
        $subtotal = $items->sum(fn (CartItem $item) => $item->quantity * $item->variant->price);
        $bonusSpent = min((int) session('bonus_spent', 0), $request->user()->bonus_points, $subtotal);
        $total = $subtotal - $bonusSpent;

        return [
            'items_count' => $items->sum('quantity'),
            'subtotal' => $subtotal,
            'bonus_spent' => $bonusSpent,
            'bonus_earned' => (int) floor($total * 0.05),
            'total' => $total,
        ];
    }
}
