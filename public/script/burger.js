document.addEventListener('DOMContentLoaded', function() {
    const burgerBtn = document.getElementById('burger-btn');
    const navBox = document.querySelector('.header__nav-box');
    burgerBtn.addEventListener('click', function() {
        const isExpanded = burgerBtn.getAttribute('aria-expanded') === 'true';
        burgerBtn.setAttribute('aria-expanded', !isExpanded);
        navBox.style.display = isExpanded ? 'none' : 'flex'; // Показать или скрыть меню
    });
});