@extends('layouts.app')

@section('content')
<div class="error-404">
  <div class="error-404__content">
    <h1 class="error-404__title">404</h1>
    <h2 class="error-404__subtitle">Страница не найдена</h2>
    <p class="error-404__text">Такой страницы нет в нашем меню.<br>Но у нас есть ароматный кофе и свежие десерты!</p>
    <div class="error-404__buttons">
      <a href="{{ route('home') }}" class="error-404__btn error-404__btn--primary">На главную</a>
      <a href="{{ route('coffee') }}" class="error-404__btn error-404__btn--secondary">Меню кофе</a>
      <a href="{{ route('desserts') }}" class="error-404__btn error-404__btn--secondary">Выбрать десерт</a>
    </div>
  </div>
</div>
@endsection
