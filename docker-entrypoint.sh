#!/bin/sh

set -e  # Encerra o script se qualquer comando falhar

echo "Aguardando o banco de dados iniciar..."

# Espera até que o PostgreSQL esteja pronto para conexões
while ! pg_isready -h db -p 5432 -U laravel_user > /dev/null 2>&1; do
  echo "Banco de dados ainda não está disponível. Aguardando..."
  sleep 1
done

echo "Banco de dados disponível."

echo "Instalando dependências com Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Gerando chave da aplicação..."
php artisan key:generate

echo "Executando migrations..."
php artisan migrate --force

echo "Iniciando PHP-FPM..."
exec php-fpm
