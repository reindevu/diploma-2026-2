@extends('layouts.app')

@section('content')
<div class="admin-container">
  <div class="account__info-box">
    <h1>Личный кабинет АДМИНИСТРАТОР</h1>
    <div class="name"><img src="{{ asset('image/account.png') }}" alt="Иконка" /><p>{{ auth()->user()->name }}</p></div>
    <div class="number">Телефон: {{ auth()->user()->phone }}</div>
    <div class="email">E-mail: {{ auth()->user()->email }}</div>
  </div>
  <div class="admin__action-box">
    <p>Выберите действие:</p>
    @include('admin.partials.nav')
    <p>Товаров: {{ $productsCount }}</p>
    <p>Заказов: {{ $ordersCount }}</p>
    <p>Клиентов: {{ $usersCount }}</p>
    <p>Категорий: {{ $categoriesCount }}</p>
  </div>
</div>
@endsection
