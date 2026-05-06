#!/usr/bin/env sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

until php -r "new PDO('pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" >/dev/null 2>&1; do
  echo "Waiting for PostgreSQL..."
  sleep 2
done

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

php artisan storage:link || true
php artisan migrate --force

PRODUCT_COUNT="$(php artisan tinker --execute='echo App\\Models\\Product::count();' 2>/dev/null || echo 0)"
if [ "$PRODUCT_COUNT" = "0" ]; then
  php artisan db:seed --force
fi

php artisan app:make-admin || true

exec "$@"
