@extends('layouts.app')

@section('content')
<div class="login-container">
  <h3>Вход в личный кабинет</h3>
  <form method="POST" action="{{ route('login.store') }}">
    @csrf
    <label for="phone">Введите номер телефона:</label>
    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+79990000000" required />
    <label for="password">Введите пароль:</label>
    <input type="password" id="password" name="password" placeholder="Пароль" required />
    <label class="remember-row"><input type="checkbox" name="remember" value="1"> Запомнить меня</label>
    <div class="link-box">
      <a href="{{ route('register') }}" class="no-account">Нет аккаунта</a>
      <a href="{{ route('admin.index') }}" class="no-account">Админка</a>
    </div>
    <button type="submit" class="login-button">Войти</button>
  </form>
</div>
@endsection
