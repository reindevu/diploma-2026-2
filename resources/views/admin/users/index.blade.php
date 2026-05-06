@extends('layouts.app')

@section('content')
<div class="admin__action-box wide-box">
  @include('admin.partials.nav')
  <h1>Пользователи</h1>
  <table class="admin-table">
    <tr><th>Имя</th><th>Телефон</th><th>Email</th><th>Роль</th><th>Баллы</th><th>Заказы</th><th>Сумма</th></tr>
    @foreach($users as $user)
      <tr><td>{{ $user->name }}</td><td>{{ $user->phone }}</td><td>{{ $user->email }}</td><td>{{ $user->role }}</td><td>{{ $user->bonus_points }}</td><td>{{ $user->orders_count }}</td><td>{{ $user->orders_sum_total ?? 0 }}</td></tr>
    @endforeach
  </table>
  {{ $users->links() }}
</div>
@endsection
