# CoffeeDoo Laravel

CoffeeDoo перенесен со статических HTML-страниц на Laravel + PostgreSQL. Верстка, CSS, изображения и JavaScript сохранены в `public/style`, `public/image`, `public/script`, `public/font`.

## Запуск

1. Создайте `.env` из примера:

```bash
cp .env.example .env
```

2. Запустите проект:

```bash
docker compose up --build
```

Сайт будет доступен по адресу: `http://localhost:10001`. Если порт занят, временно задайте `APP_PUBLISHED_PORT=10002` и откройте `http://localhost:10002`.

PostgreSQL публикуется только локально: `127.0.0.1:5432`. Если порт занят локальным PostgreSQL, временно задайте `DB_PUBLISHED_PORT=15432`.

Для продакшена за HTTPS-прокси укажите внешний адрес с HTTPS:

```env
APP_URL=https://diploma-2026-2.reindevu.ru
```

Если ассеты раздаются с отдельного домена/CDN, дополнительно задайте `ASSET_URL`.

## Основные адреса

- Главная: `http://localhost:10001/`
- Кофе: `http://localhost:10001/coffee`
- Десерты: `http://localhost:10001/desserts`
- Контакты: `http://localhost:10001/contacts`
- Личный кабинет и корзина: `http://localhost:10001/account`
- Админка: `http://localhost:10001/admin`

Отдельной страницы `/cart` нет: корзина находится в личном кабинете. Категорий в проекте две — `Кофе` и `Десерты`; новые категории не создаются, в админке можно редактировать только эти разделы.

## Доступ в админку

Администратор создается при старте контейнера из переменных:

```env
ADMIN_NAME=Администратор CoffeeDoo
ADMIN_EMAIL=admin@coffeedoo.local
ADMIN_PHONE=+79990000000
ADMIN_PASSWORD=Admin123!
```

Вход выполняется по телефону и паролю.

## Docker-команды

```bash
docker compose up --build
docker compose down
docker compose logs -f app
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan app:make-admin
```

Сброс базы:

```bash
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan app:make-admin
```

Полное удаление volume PostgreSQL:

```bash
docker compose down -v
```

Важно: пароль PostgreSQL применяется только при первом создании Docker volume. Если меняете `DB_PASSWORD` после первого запуска, удалите volume командой `docker compose down -v`.

## Uploaded images

Загружаемые изображения товаров сохраняются в `storage/app/public/uploads/products`. При старте контейнер выполняет `php artisan storage:link`, поэтому файлы доступны через `/storage/...`.
