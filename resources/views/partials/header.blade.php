<header class="header" role="banner">
  <div class="header__inner">
    <div class="header__logo-box" onclick="location.href='{{ route('home') }}'" aria-label="Перейти на главную страницу" role="link" tabindex="0" onkeydown="if(event.key==='Enter'){location.href='{{ route('home') }}'}">
      <img src="{{ asset('image/logo.png') }}" alt="Логотип" />Coffeedoo
    </div>
    <button class="burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="mobile-menu" id="burger-btn" title="Открыть меню">
      <span></span><span></span><span></span>
    </button>
    <div class="header__nav-box" role="navigation" aria-label="Основная навигация" id="mobile-menu">
      <nav><a href="{{ route('coffee') }}">Кофе</a></nav>
      <nav><a href="{{ route('desserts') }}">Десерты</a></nav>
      <nav><a href="{{ route('home') }}#review">Отзывы</a></nav>
      <nav><a href="{{ route('contacts') }}">Контакты</a></nav>
      @auth
        <nav><a href="{{ route('account.index') }}">Кабинет</a></nav>
        @if(auth()->user()->isAdmin())
          <nav><a href="{{ route('admin.index') }}">Админка</a></nav>
        @endif
      @endauth
      <button class="header__btn" onclick="location.href='{{ auth()->check() ? route('account.index') : route('login') }}'">Заказать</button>
    </div>
  </div>
</header>
