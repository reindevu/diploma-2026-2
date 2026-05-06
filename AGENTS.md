# AGENTS.md

Инструкции для Codex/AI-агентов, работающих с проектом CoffeeDoo.

## Кратко о проекте

CoffeeDoo — Laravel-приложение кофейни, перенесенное со статических HTML/CSS/JS страниц.

Технологии:
- Laravel 11
- PHP 8.2
- PostgreSQL 16
- Docker Compose
- Blade
- Eloquent ORM
- Laravel sessions/auth middleware

Главный принцип: это проект кофейни CoffeeDoo, а не универсальный интернет-магазин. Не превращать его в маркетплейс или абстрактный catalog/shop.

## Запуск

Основной запуск:

```bash
docker compose up --build
```

Адрес приложения:

```text
http://localhost:10001
```

Если порты заняты:

```bash
APP_PUBLISHED_PORT=10002 DB_PUBLISHED_PORT=15432 docker compose up --build
```

Полезные команды:

```bash
docker compose down
docker compose logs -f app
docker compose exec app php artisan route:list
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan app:make-admin
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan app:make-admin
```

В локальном окружении хоста может не быть `php` и `composer`. Проверки Laravel обычно выполнять внутри контейнера через `docker compose exec app ...`.

## Docker

Файлы:
- `docker-compose.yml`
- `docker/Dockerfile`
- `docker/apache.conf`
- `docker/entrypoint.sh`

Контейнер `app`:
- PHP 8.2 + Apache
- document root: `public/`
- порт по умолчанию: `10001:80`

Контейнер `postgres`:
- PostgreSQL 16
- публикуется только локально: `127.0.0.1:${DB_PUBLISHED_PORT:-5432}:5432`

`docker/entrypoint.sh`:
- копирует `.env.example` в `.env`, если `.env` отсутствует;
- выполняет `composer install`, если нет `vendor`;
- ждет PostgreSQL;
- генерирует `APP_KEY`, если он не задан;
- выполняет `php artisan storage:link`;
- выполняет миграции;
- запускает seed только если товаров в БД еще нет;
- создает/обновляет администратора через `php artisan app:make-admin`.

Важно: пароль PostgreSQL применяется только при первом создании Docker volume. При смене `DB_PASSWORD` нужно удалить volume через `docker compose down -v`.

## Структура

Ключевые директории:

```text
app/
  Console/Commands/MakeAdminCommand.php
  Http/Controllers/
  Http/Controllers/Admin/
  Http/Controllers/Auth/
  Http/Middleware/AdminMiddleware.php
  Http/Requests/
  Models/
database/
  migrations/
  seeders/
public/
  image/
  style/style.css
  script/
  font/
resources/views/
  layouts/app.blade.php
  partials/header.blade.php
  partials/footer.blade.php
  pages/
  products/
  auth/
  account/
  admin/
routes/web.php
```

Старые `.html` страницы удалены. Новые страницы открываются только через Laravel routes и controllers.

## Публичные маршруты

Основные публичные URL:

```text
GET /
GET /coffee
GET /desserts
GET /product/{slug}
GET /contacts
GET /login
GET /register
```

Важное правило по категориям:
- категорий ровно две: `coffee` и `desserts`;
- новые категории создавать не нужно;
- произвольного публичного `/menu/{slug}` быть не должно;
- товары в админке можно привязать только к `Кофе` или `Десерты`.

## Защищенные маршруты

Для авторизованных пользователей:

```text
GET /account
GET /account/orders
POST /cart/items
PATCH /cart/items/{cartItem}
DELETE /cart/items/{cartItem}
POST /cart/order
POST /logout
```

Админка, только `auth` + `admin` middleware:

```text
GET /admin
GET /admin/products
GET /admin/products/create
POST /admin/products
GET /admin/products/{product}/edit
PUT/PATCH /admin/products/{product}
DELETE /admin/products/{product}
GET /admin/orders
GET /admin/orders/{order}
PATCH /admin/orders/{order}
GET /admin/users
GET /admin/categories
PUT/PATCH /admin/categories/{category}
```

`/cart` как отдельная страница удален. Корзина находится на `/account`. `/checkout` тоже удален. Заказ создается из блока корзины в личном кабинете через `POST /cart/order`.

## Auth

Файлы:
- `app/Http/Controllers/Auth/AuthController.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Requests/RegisterRequest.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`

Логин выполняется по телефону и паролю.

Формат телефона:

```text
+7XXXXXXXXXX
```

Email nullable.

Remember me работает через стандартный Laravel `Auth::attempt($credentials, $request->boolean('remember'))`.

Пароль при регистрации:
- минимум 8 символов;
- нижний регистр;
- верхний регистр;
- цифра;
- спецсимвол.

## Модели и связи

Модели:
- `User`
- `Category`
- `Product`
- `ProductVariant`
- `CartItem`
- `Order`
- `OrderItem`
- `BonusTransaction`

Основные связи:
- `Category hasMany Product`
- `Product belongsTo Category`
- `Product hasMany ProductVariant`
- `User hasMany CartItem`
- `User hasMany Order`
- `User hasMany BonusTransaction`
- `CartItem belongsTo User/Product/ProductVariant`
- `Order belongsTo User`
- `Order hasMany OrderItem`
- `OrderItem belongsTo Order/Product/ProductVariant`

Отзывы на главной статичные в Blade, отдельной таблицы отзывов нет.

## Начальные данные

Seeder:

```text
database/seeders/DatabaseSeeder.php
database/seeders/AdminSeeder.php
```

Начальные категории:
- `Кофе`, slug `coffee`
- `Десерты`, slug `desserts`

Начальные товары кофе:
- Американо, `americano`, `250мл`, `300`, `image/coffee-menu/image 4.png`
- Айс Латте, `ice-latte`, `250мл`, `400`, `image/coffee-menu/image 8.png`
- Латте, `latte`, `250мл`, `300`, `image/coffee-menu/image 5.png`
- Капучино, `cappuccino`, `250мл`, `350`, `image/coffee-menu/image 3.png`
- Мокко, `mokko`, `250мл`, `350`, `image/coffee-menu/image 6.png`
- Эспрессо, `espresso`, `150мл`, `200`, `image/coffee-menu/image 2.png`

Начальные десерты:
- Брауни, `brauni`, `200гр`, `300`, `image/dessert-menu/image-2.png`
- Тирамису, `tiramisu`, `200гр`, `350`, `image/dessert-menu/image.png`
- Чизкейк, `cheesecake`, `200гр`, `400`, `image/dessert-menu/image-1.png`
- Эклер, `eclair`, `200гр`, `250`, `image/dessert-menu/image-3.png`
- Фон дан, `fon-dan`, `200гр`, `300`, `image/dessert-menu/image-5.png`

Все картинки лежат в `public/image`.

Администратор создается из env:

```env
ADMIN_NAME="Администратор CoffeeDoo"
ADMIN_EMAIL=admin@coffeedoo.local
ADMIN_PHONE=+79990000000
ADMIN_PASSWORD=Admin123!
```

Команда:

```bash
php artisan app:make-admin
```

## Корзина и заказ

Файлы:
- `app/Http/Controllers/CartController.php`
- `resources/views/account/index.blade.php`

Правила:
- корзина доступна только авторизованным пользователям;
- отдельной страницы `/cart` нет, блок корзины находится в личном кабинете `/account`;
- если корзина пуста, кнопка `Заказать` disabled;
- кнопка называется `Заказать`, не `Оплатить`, потому что оплаты нет;
- отдельной страницы `/checkout` нет;
- поле списания бонусов находится прямо в корзине;
- отдельной кнопки `Применить` для бонусов нет;
- итог пересчитывается на странице при вводе баллов;
- сервер все равно повторно валидирует бонусы при `POST /cart/order`;
- нельзя списать больше баллов, чем есть у пользователя;
- нельзя списать больше суммы заказа;
- цены заказа сохраняются в `order_items.price`, чтобы история не менялась при изменении товара;
- после успешного заказа корзина очищается;
- начисление бонусов: 5% от суммы после списания бонусов, округление вниз.

JSON endpoints корзины возвращают объект с:

```json
{
  "success": true,
  "message": "...",
  "data": {}
}
```

## Бонусы и отмена заказа

Бонусы пишутся в `bonus_transactions`.

При создании заказа:
- списанные баллы пишутся как `type = spent`, `amount < 0`;
- начисленные баллы пишутся как `type = earned`, `amount > 0`;
- `users.bonus_points` сразу обновляется.

При переводе заказа в `cancelled` через админку:
- списанные баллы возвращаются пользователю;
- начисленные за заказ баллы снимаются;
- создаются транзакции:
  - `refund`
  - `earned_cancel`
- повторное сохранение уже отмененного заказа не должно задваивать бонусные операции.

Эта логика находится в `app/Http/Controllers/Admin/OrderController.php`.

## Админка

Админ middleware:

```text
app/Http/Middleware/AdminMiddleware.php
```

Админские контроллеры:
- `Admin/DashboardController.php`
- `Admin/ProductController.php`
- `Admin/OrderController.php`
- `Admin/UserController.php`
- `Admin/CategoryController.php`

Категории:
- создание удалено;
- удаление удалено;
- редактируются только `coffee` и `desserts`;
- slug в форме disabled и не меняется;
- товары можно создавать только в этих двух категориях.

Товары:
- CRUD;
- загрузка изображения через `storage/app/public/uploads/products`;
- публичный URL загруженных файлов идет через `/storage/...`;
- цена и объем хранятся в `product_variants`, даже если вариант один;
- товар без активного варианта не должен показываться в меню.

Заказы:
- список;
- подробная страница;
- смена статуса;
- статусы в БД на английском:
  - `new`
  - `processing`
  - `completed`
  - `cancelled`
- пользователю показывается русский перевод через `Order::statusLabel()`.

Пользователи:
- список пользователей;
- роль;
- бонусы;
- количество и сумма заказов.

## Blade и верстка

Главное правило: верстку CoffeeDoo сохранять максимально близко к исходной. Не делать редизайн без запроса.

Основные файлы:
- `resources/views/layouts/app.blade.php`
- `resources/views/partials/header.blade.php`
- `resources/views/partials/footer.blade.php`
- `resources/views/pages/home.blade.php`
- `resources/views/pages/menu.blade.php`
- `resources/views/products/show.blade.php`
- `public/style/style.css`
- `public/script/burger.js`
- `public/script/counter.js`

CSS подключается с cache-busting:

```blade
{{ asset('style/style.css') }}?v={{ filemtime(public_path('style/style.css')) }}
```

Если пользователь говорит, что CSS не применился, сначала проверить кеш и фактический CSS с сервера:

```bash
curl -s http://127.0.0.1:10001/style/style.css | rg -n "нужный-селектор"
```

Не ломать:
- классы старой верстки;
- burger menu;
- пути к изображениям;
- общий внешний вид карточек меню и страниц товара.

## Главная страница

Файл:

```text
resources/views/pages/home.blade.php
```

Блок отзывов сейчас статичный, ровно 3 отзыва:
- Антонов Александр
- Прохоров Илья
- Жиров Дмитрий

Не подключать отзывы главной к БД без отдельного требования.

## SEO

SEO задается через `resources/views/layouts/app.blade.php` и массив `$meta` из контроллеров.

Публичные страницы должны иметь:
- title;
- description;
- canonical;
- Open Graph;
- Twitter Card;
- robots;
- JSON-LD там, где уместно.

Служебные страницы должны быть `noindex, nofollow`:
- login;
- register;
- account;
- cart;
- admin;
- 404.

`public/robots.txt` должен соответствовать текущим URL без `.html`.

## Ассеты и загрузки

Статические ассеты:

```text
public/image
public/style
public/script
public/font
```

Пользовательские загрузки:

```text
storage/app/public/uploads/products
```

Ссылка:

```bash
php artisan storage:link
```

`public/storage` не коммитить.

## Git и файлы

Не коммитить:
- `.env`
- `vendor`
- `node_modules`
- IDE-файлы
- логи
- compiled views/cache
- `public/storage`
- пользовательские uploads, если они не нужны как исходные ассеты.

Старые `.html` страницы не нужны и не должны возвращаться.

## Проверки после изменений

Минимальный набор:

```bash
docker compose config
docker compose exec app php artisan route:list
```

Публичные страницы:

```bash
curl -I http://127.0.0.1:10001/
curl -I http://127.0.0.1:10001/coffee
curl -I http://127.0.0.1:10001/desserts
curl -I http://127.0.0.1:10001/product/espresso
curl -I http://127.0.0.1:10001/contacts
```

Защищенные страницы:

```bash
curl -I http://127.0.0.1:10001/account
curl -I http://127.0.0.1:10001/admin
```

Ожидаемо без авторизации: redirect на `/login`.

Для проверки админки через curl:
1. получить CSRF с `/login`;
2. войти по `ADMIN_PHONE`/`ADMIN_PASSWORD`;
3. открыть нужный URL с cookie.

Пример проверок маршрутов:

```bash
docker compose exec app php artisan route:list --path=cart
docker compose exec app php artisan route:list --path=admin/categories
docker compose exec app php artisan route:list --path=checkout
```

`/checkout` должен отсутствовать.
`GET /cart` должен отсутствовать; рабочими остаются только `POST /cart/items`, `PATCH /cart/items/{cartItem}`, `DELETE /cart/items/{cartItem}` и `POST /cart/order`.

## Стиль разработки

При изменениях:
- использовать стандартные возможности Laravel;
- не добавлять самописный мини-фреймворк;
- держать контроллеры и Blade простыми;
- использовать Form Requests там, где есть сложная валидация;
- не писать новые публичные PHP-файлы в `public/`;
- не менять бизнес-логику бонусов/категорий без явного запроса;
- сохранять русские тексты интерфейса;
- URL должны быть чистые, без `.html`.
