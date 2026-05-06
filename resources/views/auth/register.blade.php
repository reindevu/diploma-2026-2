@extends('layouts.app')

@section('content')
<div class="reg-container">
  <h3>Регистрация</h3>
  <form method="POST" action="{{ route('register.store') }}">
    @csrf
    <label for="name">Введите имя:</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Иван Иванов" required />
    <label for="phone">Введите номер телефона:</label>
    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+79990000000" required />
    <label for="email">Введите e-mail:</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="mail@example.ru" />
    <label for="password">Введите пароль:</label>
    <input type="password" id="password" name="password" placeholder="Min8 Aa 1 !" required />
    <label for="password_confirmation">Повторите пароль:</label>
    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Повтор пароля" required />
    <button type="submit" class="login-button">Зарегистрироваться</button>
  </form>
</div>
@endsection
