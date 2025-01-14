# Variables
DOCKER_COMPOSE = docker-compose

# Default target
.PHONY: help
help:
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@echo "  up                Start the application and database containers"
	@echo "  up-build          Build and start the application and database containers"
	@echo "  down              Stop the application and database containers"
	@echo "  restart           Restart the application and database containers"
	@echo "  restart-app       Restart the app container"
	@echo "  restart-web       Restart the web container"
	@echo "  restart-db        Restart the db container"
	@echo "  migrate           Run database migrations"
	@echo "  seed              Seed the database"
	@echo "  install           Install PHP dependencies"
	@echo "  update            Update PHP dependencies"
	@echo "  test              Run tests"
	@echo "  logs              View logs for all containers"
	@echo "  logs-app          View logs for the app container"
	@echo "  logs-web          View logs for the web container"
	@echo "  logs-db           View logs for the db container"
	@echo "  bash              Access the app container via bash"
	@echo "  db                Access the MySQL shell"
	@echo "  inspect-db        Inspect the db container"
	@echo "  cache-clear       Clear the application cache"

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: up-build
up-build:
	$(DOCKER_COMPOSE) up -d --build

.PHONY: down
down:
	$(DOCKER_COMPOSE) down

.PHONY: restart
restart:
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d

.PHONY: restart-app
restart-app:
	$(DOCKER_COMPOSE) restart app

.PHONY: restart-web
restart-web:
	$(DOCKER_COMPOSE) restart web

.PHONY: restart-db
restart-db:
	$(DOCKER_COMPOSE) restart db

.PHONY: migrate
migrate:
	$(DOCKER_COMPOSE) exec app php artisan migrate

.PHONY: seed
seed:
	$(DOCKER_COMPOSE) exec app php artisan db:seed

.PHONY: install
install:
	$(DOCKER_COMPOSE) exec app composer install

.PHONY: update
update:
	$(DOCKER_COMPOSE) exec app composer update

.PHONY: test
test:
	$(DOCKER_COMPOSE) exec app php artisan test

.PHONY: logs
logs:
	$(DOCKER_COMPOSE) logs -f

.PHONY: logs-app
logs-app:
	$(DOCKER_COMPOSE) logs -f app

.PHONY: logs-web
logs-web:
	$(DOCKER_COMPOSE) logs -f web

.PHONY: logs-db
logs-db:
	$(DOCKER_COMPOSE) logs -f db

.PHONY: bash
bash:
	$(DOCKER_COMPOSE) exec app bash

.PHONY: db
db:
	$(DOCKER_COMPOSE) exec db mysql -u$$(grep DB_USERNAME .env | cut -d '=' -f2) -p$$(grep DB_PASSWORD .env | cut -d '=' -f2) $$(grep DB_DATABASE .env | cut -d '=' -f2)

.PHONY: inspect-db
inspect-db:
	docker inspect db

.PHONY: cache-clear
cache-clear:
	$(DOCKER_COMPOSE) exec app php artisan cache:clear
