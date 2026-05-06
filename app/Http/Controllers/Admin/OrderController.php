<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusTransaction;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::with('user')->latest()->paginate(20),
            'meta' => ['title' => 'Заказы | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['user', 'items']),
            'statuses' => Order::STATUSES,
            'meta' => ['title' => 'Заказ #'.$order->id.' | CoffeeDoo', 'robots' => 'noindex, nofollow'],
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(array_keys(Order::STATUSES))]]);

        DB::transaction(function () use ($order, $data): void {
            $oldStatus = $order->status;
            $newStatus = $data['status'];

            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                $this->reverseOrderBonuses($order);
            }

            $order->update($data);
        });

        return back()->with('status', 'Статус заказа обновлен.');
    }

    private function reverseOrderBonuses(Order $order): void
    {
        $order->loadMissing('user');

        if ($order->bonus_spent > 0) {
            BonusTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'amount' => $order->bonus_spent,
                'type' => 'refund',
                'comment' => 'Возврат списанных баллов за отмену заказа #'.$order->id,
            ]);
        }

        if ($order->bonus_earned > 0) {
            BonusTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'amount' => -$order->bonus_earned,
                'type' => 'earned_cancel',
                'comment' => 'Отмена начисления баллов за заказ #'.$order->id,
            ]);
        }

        $order->user->forceFill([
            'bonus_points' => max(0, $order->user->bonus_points + $order->bonus_spent - $order->bonus_earned),
        ])->save();
    }
}
