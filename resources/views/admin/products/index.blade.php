@extends('layouts.app')

@section('content')
<div class="admin__action-box wide-box">
  @include('admin.partials.nav')
  <h1>Товары</h1>
  <table class="admin-table">
    <tr><th>Название</th><th>Категория</th><th>Цена</th><th>Активен</th><th></th></tr>
    @foreach($products as $product)
      <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $product->category->name }}</td>
        <td>{{ $product->variants->min('price') }} руб.</td>
        <td>{{ $product->active ? 'Да' : 'Нет' }}</td>
        <td>
          <a href="{{ route('admin.products.edit', $product) }}">Редактировать</a>
          <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-form">@csrf @method('DELETE')<button type="submit">Удалить</button></form>
        </td>
      </tr>
    @endforeach
  </table>
  {{ $products->links() }}
</div>
@endsection
