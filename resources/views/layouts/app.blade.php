@php
    $meta = $meta ?? [];
    $title = $meta['title'] ?? 'CoffeeDoo';
    $description = $meta['description'] ?? 'Кофейня CoffeeDoo — авторский кофе и домашние десерты.';
    $robots = $meta['robots'] ?? 'index, follow';
    $canonical = $meta['canonical'] ?? url()->current();
    $image = $meta['image'] ?? asset('image/logo.png');
@endphp
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="{{ $robots }}">
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('style/style.css') }}?v={{ filemtime(public_path('style/style.css')) }}" />
    <link rel="shortcut icon" href="{{ asset('image/logo.png') }}" type="image/x-icon" />
    @stack('head')
    @if(!empty($meta['jsonLd']))
      <script type="application/ld+json">@json($meta['jsonLd'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
    @endif
  </head>
  <body>
    @include('partials.header')
    <main class="main">
      <div class="container">
        @if(session('status'))
          <p class="flash-message">{{ session('status') }}</p>
        @endif
        @if($errors->any())
          <div class="flash-message flash-message--error">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif
        @yield('content')
      </div>
    </main>
    @include('partials.footer')
    <script src="{{ asset('script/burger.js') }}"></script>
    @stack('scripts')
  </body>
</html>
