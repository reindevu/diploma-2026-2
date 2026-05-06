@extends('layouts.app')

@section('content')
<div class="account-container">
  <div class="account__info-box">
    <h1>Личный кабинет</h1>
    <div class="name"><img src="{{ asset('image/account.png') }}" alt="Иконка" /><p>{{ $user->name }}</p></div>
    <div class="number">Телефон: {{ $user->phone }}</div>
    <div class="email">E-mail: {{ $user->email ?: 'не указан' }}</div>
    <div class="point">Баллы: {{ $user->bonus_points }} шт.</div>
    <form method="POST" action="{{ route('logout') }}">@csrf <button class="pay-btn" type="submit">Выйти</button></form>
  </div>
  <div class="account__history-box">
    <p class="big">История заказов:</p>
    @forelse($orders as $order)
      <div class="history">
        <p>{{ $order->items->pluck('product_name')->join(', ') }}</p>
        <span class="dots"></span>
        <p>{{ $order->total }} руб.</p>
      </div>
      <p>{{ $order->statusLabel() }}</p>
    @empty
      <p>Заказов пока нет.</p>
    @endforelse
  </div>
  <div class="account__cart-box" data-cart>
    <div class="top">
      <p class="big">Корзина:</p>
      @forelse($cartItems as $item)
        <div class="cart_tovar" data-item="{{ $item->id }}">
          <p>{{ $item->product->name }} ({{ $item->variant->name }})</p>
          <div class="tovar__inner">
            <div class="counter">
              <button class="decrease" data-action="dec" type="button">-</button>
              <span class="number">{{ $item->quantity }}</span>
              <button class="increase" data-action="inc" type="button">+</button>
            </div>
            <p>{{ $item->variant->price }} руб.</p>
            <button class="icon-button" data-action="delete" type="button"><img src="{{ asset('image/recycle-bin.png') }}" alt="Корзина" /></button>
          </div>
        </div>
      @empty
        <p data-empty-cart>Корзина пуста.</p>
      @endforelse
    </div>
    <div class="bottom">
      <form method="POST" action="{{ route('cart.order') }}" data-order-form>
        @csrf
        <div class="bonus-box" data-bonus @if($cartItems->isEmpty()) hidden @endif>
          <p>Использовать баллы:</p>
          <input class="bonus-input" type="number" name="bonus_spent" min="0" max="{{ min($user->bonus_points, $summary['subtotal']) }}" value="{{ $summary['bonus_spent'] }}" placeholder="0" data-bonus-input>
          <p>всего баллов: {{ $user->bonus_points }}</p>
        </div>
        <p class="big">Итого:</p>
        <div class="price-box" data-subtotal="{{ $summary['subtotal'] }}" data-user-bonus="{{ $user->bonus_points }}">
          <p class="big"><span data-total>{{ $summary['total'] }}</span> руб.</p>
          @if($cartItems->isEmpty())
            <button class="pay-btn" type="submit" disabled data-order-btn>Заказать</button>
          @else
            <button class="pay-btn" type="submit" data-order-btn>Заказать</button>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const token = '{{ csrf_token() }}';
const priceBox = document.querySelector('[data-subtotal]');
const bonusInput = document.querySelector('[data-bonus-input]');
function maxBonus() {
  return Math.min(Number(priceBox?.dataset.userBonus || 0), Number(priceBox?.dataset.subtotal || 0));
}
function recalculateTotal() {
  if (!priceBox) return;
  const subtotal = Number(priceBox.dataset.subtotal || 0);
  const bonus = Math.min(Math.max(Number(bonusInput?.value || 0), 0), maxBonus());
  if (bonusInput) {
    bonusInput.value = bonus;
    bonusInput.max = maxBonus();
  }
  document.querySelector('[data-total]').innerText = subtotal - bonus;
}
function updateSummary(data) {
  if (data?.subtotal !== undefined && priceBox) priceBox.dataset.subtotal = data.subtotal;
  const orderBtn = document.querySelector('[data-order-btn]');
  const bonusForm = document.querySelector('[data-bonus]');
  const top = document.querySelector('.top');
  if (data?.items_count === 0) {
    orderBtn?.setAttribute('disabled', 'disabled');
    bonusForm?.setAttribute('hidden', 'hidden');
    if (!document.querySelector('[data-empty-cart]')) {
      top.insertAdjacentHTML('beforeend', '<p data-empty-cart>Корзина пуста.</p>');
    }
  }
  recalculateTotal();
}
bonusInput?.addEventListener('input', recalculateTotal);
document.querySelectorAll('[data-item]').forEach((row) => {
  row.addEventListener('click', async (event) => {
    const action = event.target.closest('[data-action]')?.dataset.action;
    if (!action) return;
    const id = row.dataset.item;
    if (action === 'delete') {
      const res = await fetch(`/cart/items/${id}`, {method: 'DELETE', headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json'}});
      const json = await res.json();
      if (json.success) row.remove();
      updateSummary(json.data);
      return;
    }
    const number = row.querySelector('.number');
    const quantity = Math.max(1, parseInt(number.innerText, 10) + (action === 'inc' ? 1 : -1));
    const res = await fetch(`/cart/items/${id}`, {method: 'PATCH', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json'}, body: JSON.stringify({quantity})});
    const json = await res.json();
    if (json.success) number.innerText = quantity;
    updateSummary(json.data);
  });
});
recalculateTotal();
</script>
@endpush
