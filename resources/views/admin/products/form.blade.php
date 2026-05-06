@extends('layouts.app')

@section('content')
@php($variants = old('variants', $product->exists ? $product->variants->toArray() : [['name' => '250 мл', 'measure_value' => '', 'measure_unit' => 'мл', 'price' => '', 'active' => true, 'sort_order' => 0]]))
<div class="admin__action-box wide-box">
  @include('admin.partials.nav')
  <h1>{{ $product->exists ? 'Редактирование товара' : 'Создание товара' }}</h1>
  <form method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="admin-form">
    @csrf
    @if($product->exists) @method('PUT') @endif
    <label class="admin-form-row">
      <span>Категория</span>
      <select name="category_id">@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>
    </label>
    <label class="admin-form-row">
      <span>Название</span>
      <input name="name" value="{{ old('name', $product->name) }}" placeholder="Капучино" required>
    </label>
    <label class="admin-form-row">
      <span>Slug</span>
      <input name="slug" value="{{ old('slug', $product->slug) }}" placeholder="cappuccino" required>
    </label>
    <label class="admin-form-row admin-form-row--top">
      <span>Описание</span>
      <textarea name="description" placeholder="Описание товара" required>{{ old('description', $product->description) }}</textarea>
    </label>
    <label class="admin-form-row">
      <span>Изображение</span>
      <input type="file" name="image" @required(!$product->exists)>
    </label>
    @if($product->exists)
      <div class="admin-form-row admin-form-row--top">
        <span>Текущее</span>
        <img class="admin-thumb" src="{{ $product->imageUrl() }}" alt="">
      </div>
    @endif
    <label class="admin-form-row admin-form-row--check">
      <span></span>
      <span><input type="checkbox" name="active" value="1" @checked(old('active', $product->active))> Активен</span>
    </label>
    <label class="admin-form-row">
      <span>Сортировка</span>
      <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" placeholder="0">
    </label>
    <label class="admin-form-row">
      <span>SEO title</span>
      <input name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" placeholder="Заголовок">
    </label>
    <label class="admin-form-row admin-form-row--top">
      <span>SEO description</span>
      <textarea name="seo_description" placeholder="Описание для поисковиков">{{ old('seo_description', $product->seo_description) }}</textarea>
    </label>
    <h4>Варианты</h4>
    @foreach($variants as $i => $variant)
      <div class="variant-row">
        <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant['id'] ?? '' }}">
        <input name="variants[{{ $i }}][name]" value="{{ $variant['name'] ?? '' }}" placeholder="250 мл" aria-label="Название варианта" required>
        <input type="number" name="variants[{{ $i }}][measure_value]" value="{{ $variant['measure_value'] ?? '' }}" placeholder="250" aria-label="Объем или вес">
        <input name="variants[{{ $i }}][measure_unit]" value="{{ $variant['measure_unit'] ?? '' }}" placeholder="мл" aria-label="Единица измерения">
        <input type="number" name="variants[{{ $i }}][price]" value="{{ $variant['price'] ?? '' }}" placeholder="350" aria-label="Цена" required>
        <input type="number" name="variants[{{ $i }}][sort_order]" value="{{ $variant['sort_order'] ?? 0 }}" placeholder="0" aria-label="Сортировка">
        <label class="variant-row__check"><input type="checkbox" name="variants[{{ $i }}][active]" value="1" @checked($variant['active'] ?? true)> Активен</label>
      </div>
    @endforeach
    <button class="login-button" type="submit">Сохранить</button>
  </form>
</div>
@endsection
