<footer class="footer">
  <div class="footer__inner">
    <div class="footer__nav-box">
      <div class="footer-logo">
        <img src="{{ asset('image/logo.png') }}" alt="Логотип" />
        <p>Coffeedoo</p>
      </div>
      <div class="footer-nav">
        <nav><a href="{{ route('coffee') }}">Кофе</a></nav>
        <nav><a href="{{ route('home') }}#review">Отзывы</a></nav>
        <nav><a href="{{ route('desserts') }}">Десерты</a></nav>
        <nav><a href="{{ route('contacts') }}">Контакты</a></nav>
      </div>
    </div>
    <div class="footer__info">
      <p>Реквезиты</p>
      <p>ИП Кириллов Максим Владимирович</p>
      <p>ИНН 773213546784</p>
      <p>C077eDo@mail.ru</p>
    </div>
    <div class="footer__social">
      <a href="#"><img src="{{ asset('image/vk.png') }}" alt="vk" /></a>
      <a href="#"><img src="{{ asset('image/inst.png') }}" alt="inst" /></a>
      <a href="#"><img src="{{ asset('image/tg.png') }}" alt="tg" /></a>
    </div>
  </div>
</footer>
