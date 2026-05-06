@extends('layouts.app')

@section('content')
<div class="admin__action-box wide-box">
  @include('admin.partials.nav')
  <h1>Заказ #{{ $order->id }}</h1>
  <p>Покупатель: {{ $order->name }} · {{ $order->phone }} · {{ $order->email }}</p>
  <p>Баллы: списано {{ $order->bonus_spent }}, начислено {{ $order->bonus_earned }}</p>
  <table class="admin-table">
    <tr><th>Товар</th><th>Вариант</th><th>Кол-во</th><th>Цена</th><th>Итого</th></tr>
    @foreach($order->items as $item)
      <tr><td>{{ $item->product_name }}</td><td>{{ $item->variant_name }}</td><td>{{ $item->quantity }}</td><td>{{ $item->price }}</td><td>{{ $item->line_total }}</td></tr>
    @endforeach
  </table>
  <p>Итого: {{ $order->total }} руб.</p>
  <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="admin-form">
    @csrf @method('PATCH')
    <label>Статус<select name="status">@foreach($statuses as $status => $label)<option value="{{ $status }}" @selected($order->status === $status)>{{ $label }}</option>@endforeach</select></label>
    <button class="login-button" type="submit">Сохранить</button>
  </form>
</div>
@endsection
