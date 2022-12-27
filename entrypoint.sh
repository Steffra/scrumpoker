#!/bin/bash



cd ../backend
sudo cp /app/backend/.env.example /app/backend/.env
sudo rm -rf vendor
sudo touch /app/backend/database/database.sqlite
sudo composer install
sudo php /app/backend/artisan key:generate
php artisan migrate:reset
sudo php /app/backend/artisan migrate
sudo php /app/backend/artisan db:seed
sudo php /app/backend/artisan serve &
sudo php /app/backend/artisan websockets:serve &

cd ../frontend
sudo rm -rf node_modules
npm install
npm run dev
exec "$@"
