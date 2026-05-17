COMPOSE = docker-compose

.PHONY: up down restart build logs shell migrate seed fresh test tinker

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
