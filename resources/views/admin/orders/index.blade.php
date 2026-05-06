@extends('layouts.app')

@section('content')
<div class="admin__action-box wide-box">
  @include('admin.partials.nav')
  <h1>Заказы</h1>
  <table class="admin-table">
    <tr><th>ID</th><th>Покупатель</th><th>Телефон</th><th>Сумма</th><th>Статус</th><th></th></tr>
    @foreach($orders as $order)
      <tr><td>#{{ $order->id }}</td><td>{{ $order->name }}</td><td>{{ $order->phone }}</td><td>{{ $order->total }} руб.</td><td>{{ $order->statusLabel() }}</td><td><a href="{{ route('admin.orders.show', $order) }}">Открыть</a></td></tr>
    @endforeach
  </table>
  {{ $orders->links() }}
</div>
@endsection
