<?php

namespace App\Http\Controllers;

use App\Models\BonusTransaction;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $variant = ProductVariant::with('product')->where('active', true)->findOrFail($data['product_variant_id']);
        abort_unless($variant->product->active, 404);

        $item = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'product_variant_id' => $variant->id,
        ]);
        $item->product_id = $variant->product_id;
        $item->quantity = min(99, ($item->exists ? $item->quantity : 0) + (int) $data['quantity']);
        $item->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Товар добавлен в корзину.', 'data' => $this->summaryData($request)]);
        }

        return redirect()->route('account.index')->with('status', 'Товар добавлен в корзину.');
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeCartItem($request, $cartItem);
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        $cartItem->update(['quantity' => $data['quantity']]);

        return response()->json(['success' => true, 'message' => 'Количество обновлено.', 'data' => $this->summaryData($request)]);
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);
        $cartItem->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Товар удален.', 'data' => $this->summaryData($request)]);
        }

        return back()->with('status', 'Товар удален.');
    }

    public function order(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'bonus_spent' => ['nullable', 'integer', 'min:0'],
        ]);
        $items = $this->items($request);

        if ($items->isEmpty()) {
            return redirect()->route('account.index')->withErrors(['cart' => 'Корзина пуста.']);
        }

        $bonusSpent = (int) ($data['bonus_spent'] ?? 0);
        $summary = $this->summaryData($request, $bonusSpent);

        if ($bonusSpent !== $summary['bonus_spent']) {
            return back()->withErrors(['bonus_spent' => 'Некорректное количество бонусных баллов.'])->withInput();
        }

        DB::transaction(function () use ($data, $user, $items, $summary) {
            $order = Order::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'subtotal' => $summary['subtotal'],
                'bonus_spent' => $summary['bonus_spent'],
                'bonus_earned' => $summary['bonus_earned'],
                'total' => $summary['total'],
                'status' => 'new',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant->name,
                    'price' => $item->variant->price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->variant->price * $item->quantity,
                ]);
            }

            if ($summary['bonus_spent'] > 0) {
                BonusTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => -$summary['bonus_spent'],
                    'type' => 'spent',
                    'comment' => 'Списание за заказ #'.$order->id,
                ]);
            }

            if ($summary['bonus_earned'] > 0) {
                BonusTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => $summary['bonus_earned'],
                    'type' => 'earned',
                    'comment' => 'Начисление за заказ #'.$order->id,
                ]);
            }

            $user->forceFill([
                'bonus_points' => $user->bonus_points - $summary['bonus_spent'] + $summary['bonus_earned'],
            ])->save();

            CartItem::where('user_id', $user->id)->delete();
            session()->forget('bonus_spent');
        });

        return redirect()->route('account.index')->with('status', 'Заказ оформлен.');
    }

    private function items(Request $request)
    {
        return $request->user()->cartItems()->with(['product', 'variant'])->get();
    }

    private function summaryData(Request $request, ?int $bonusSpent = null): array
    {
        $items = $this->items($request);
        $subtotal = $items->sum(fn (CartItem $item) => $item->quantity * $item->variant->price);
        $bonusSpent ??= (int) session('bonus_spent', 0);
        $bonusSpent = min($bonusSpent, $request->user()->bonus_points, $subtotal);
        $total = $subtotal - $bonusSpent;

        return [
            'items_count' => $items->sum('quantity'),
            'subtotal' => $subtotal,
            'bonus_spent' => $bonusSpent,
            'bonus_earned' => (int) floor($total * 0.05),
            'total' => $total,
        ];
    }

    private function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);
    }
}
