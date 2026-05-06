@extends('layouts.app')

@section('content')
<div class="admin__action-box wide-box">
  @include('admin.partials.nav')
  <h1>Категории</h1>
  @foreach($categories as $category)
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="admin-form category-row">
      @csrf @method('PUT')
      <input name="name" value="{{ $category->name }}" placeholder="Название" required>
      <input value="{{ $category->slug }}" placeholder="slug" disabled>
      <input name="description" value="{{ $category->description }}" placeholder="Описание">
      <input type="number" name="sort_order" value="{{ $category->sort_order }}" placeholder="0">
      <label><input type="checkbox" name="active" value="1" @checked($category->active)> Активна</label>
      <span>Товаров: {{ $category->products_count }}</span>
      <a href="{{ $category->slug === 'coffee' ? route('coffee') : route('desserts') }}" target="_blank">Открыть страницу</a>
      <button type="submit">Сохранить</button>
    </form>
  @endforeach
</div>
@endsection
