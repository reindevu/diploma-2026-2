@extends('layouts.app')

@section('content')
<div class="card-container">
  <h1>{{ $product->name }}</h1>
  <div class="card-box">
    <div class="card__img-box"><img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" /></div>
    <div class="card__content-box">
      <div class="card__content">
        <p>{{ $product->description }}</p>
        <form action="{{ route('cart.items.store') }}" method="POST" class="product-cart-form">
          @csrf
          <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
          <div class="card-info">
            <p>Объем: {{ $variant->name }}</p>
            <p>Цена: {{ $variant->price }} руб.</p>
          </div>
          <div class="card__btn-box">
            <div class="counter">
              <button type="button" class="decrease">-</button>
              <span class="number">1</span>
              <button type="button" class="increase">+</button>
            </div>
            <input type="hidden" name="quantity" value="1" class="quantity-input">
            <button class="buy-btn" type="submit">Купить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('script/counter.js') }}"></script>
<script>
document.querySelectorAll('.product-cart-form').forEach((form) => {
  form.addEventListener('click', () => {
    const number = form.querySelector('.number');
    const input = form.querySelector('.quantity-input');
    setTimeout(() => input.value = number.innerText, 0);
  });
});
</script>
@endpush
