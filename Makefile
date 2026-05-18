COMPOSE = docker-compose

.PHONY: setup up down restart build logs shell migrate seed fresh test tinker

setup:
	@test -f .env || cp .env.example .env
	$(COMPOSE) up -d --build
	$(COMPOSE) exec app composer install
	$(COMPOSE) exec app php artisan key:generate
	$(COMPOSE) exec app php artisan migrate --force --seed
	$(COMPOSE) exec app npm install
	$(COMPOSE) exec app npm run build

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

restart: down up

build:
	$(COMPOSE) build --no-cache

logs:
	$(COMPOSE) logs -f app

shell:
	$(COMPOSE) exec app bash

migrate:
	$(COMPOSE) exec app php artisan migrate

seed:
	$(COMPOSE) exec app php artisan db:seed

fresh:
	$(COMPOSE) exec app php artisan migrate:fresh --seed

test:
	$(COMPOSE) exec app php artisan test

tinker:
	$(COMPOSE) exec app php artisan tinker
