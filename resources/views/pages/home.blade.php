@extends('layouts.app')

@section('content')
<div class="first-section">
  <div class="first-section__img-box"><img src="{{ asset('image/index.png') }}" alt="Баннер" /></div>
  <div class="first-section__content-box">
    <div class="first-section__content">
      <h1>ЗЕРНОВОЙ КОФЕ ВЫСШЕГО КЛАССА</h1>
      <p>Отличный, бодрящий и безумно ароматный кофе ждет вас ежедневно с 8:00 до 22:00</p>
      <p>Без перерывов и выходных</p>
      <button class="first-section-btn" onclick="location.href='{{ route('login') }}'">Оставить заявку</button>
    </div>
  </div>
</div>
<div class="second-section">
  <h3>Почему мы?</h3>
  <p>Coffedoo - на первый взгляд обычная кофейня, но после вашего первого визита, вы ощутите невероятные ароматы, самых разнообразных сортов кофею. Вкусите этот особенный вкус, будете в восторге от интерьера и не устоите перед желанием посетить нашу кофейню снова</p>
  <div class="advantages-box">
    <div class="advantage"><h2>01</h2><p>Всегда свежие десерты от кондитеров и домашняя выпечка</p></div>
    <div class="advantage"><h2>02</h2><p>Качественное зерно со всех уголков планеты</p></div>
    <div class="advantage"><h2>03</h2><p>Бариста с радостью раскажут о зерне,на котором приготовлен ваш кофе</p></div>
  </div>
</div>
<div class="third-section">
  <h3 id="review">Отзывы клиентов</h3>
  <div class="review-container">
    <div class="review-box">
      <div class="review">
        <h4>Долго искали и наконец нашли!</h4>
        <p>Мы давно искали хорошую кофейню, и наконец нашли ее. Нам безумно понравился интерьер и кофе.</p>
      </div>
      <div class="reviewer"><img src="{{ asset('image/review-1.png') }}" alt="Отзыв" /><p>Антонов Александр</p></div>
    </div>
    <div class="review-box">
      <div class="review">
        <h4>Лучший сервс, который я видел.</h4>
        <p>Сотрудники очень отзывчивые и помогают решить любой вопрос, и с радостью проконсультируют вас.</p>
      </div>
      <div class="reviewer"><img src="{{ asset('image/review-2.png') }}" alt="Отзыв" /><p>Прохоров Илья</p></div>
    </div>
    <div class="review-box">
      <div class="review">
        <h4>Самое лучшее место в городе.</h4>
        <p>В таких классных местах я не был никогда в своей жизни мне понравилось абсолютно все.</p>
      </div>
      <div class="reviewer"><img src="{{ asset('image/review-3.png') }}" alt="Отзыв" /><p>Жиров Дмитрий</p></div>
    </div>
  </div>
</div>
@endsection
