@extends('layouts.app')

@section('content')
<div class="menu-container">
  <h1>{{ $category->name }}</h1>
  <p>{{ $category->description }}</p>
  <div class="menu-box">
    @foreach($products as $product)
      <div class="card" onclick="location.href='{{ route('products.show', $product->slug) }}'">
        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" />
        <div class="price">
          <h4>{{ $product->name }}</h4>
          <p>{{ $product->activeVariants->min('price') }} ₽</p>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
