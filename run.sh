#!/bin/sh

# Обработка ошибок
set -e

# Запуск Docker Compose
echo "Starting Docker Compose"
docker-compose -f "docker-compose.yml" up --build --force-recreate --remove-orphans -d

# Ожидание запуска контейнеров
echo "Waiting for containers to be up and running..."
sleep 10

# Установка зависимостей Composer
echo "Installing Composer dependencies"
docker-compose exec adventure-time.php composer install

# Установка зависимостей Composer
echo "Installing Composer dependencies"
docker-compose exec -it adventure-time.php php bin/phpunit
echo "All done!"
