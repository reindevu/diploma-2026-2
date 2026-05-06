@extends('layouts.app')

@section('content')
<div class="account__history-box wide-box">
  <p class="big">История заказов:</p>
  @forelse($orders as $order)
    <div class="history"><p>Заказ #{{ $order->id }}: {{ $order->items->pluck('product_name')->join(', ') }}</p><span class="dots"></span><p>{{ $order->total }} руб.</p></div>
    <p>{{ $order->statusLabel() }} · списано {{ $order->bonus_spent }} · начислено {{ $order->bonus_earned }}</p>
  @empty
    <p>Заказов пока нет.</p>
  @endforelse
</div>
@endsection
