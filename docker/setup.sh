#!/bin/bash

docker compose up -d

sleep 5

sudo chown -R $USER:$USER src/
sudo chmod -R 777 src/

sleep 2

cp src/.env.example src/.env

docker exec stock-app composer install
sleep 2

sudo chmod -R 777 src/.env
docker exec stock-app php artisan key:generate
docker exec stock-app php artisan migrate:fresh --seed
