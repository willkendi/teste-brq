#!/bin/sh

echo "Aguardando o banco de dados iniciar..."

while ! pg_isready -h db -p 5432 -U laravel_user > /dev/null 2>&1; do
  sleep 1
done

echo "Banco de dados disponível!"

composer install --no-interaction --prefer-dist --optimize-autoloader

php artisan key:generate

php artisan migrate --force

exec php-fpm
