SHELL = /bin/bash

.PHONY: up down restart composer-install console cache-clear

build:      ## Build the application
	docker compose up --build -d
	docker compose exec php composer install
	docker compose exec php bin/console lexik:jwt:generate-keypair --overwrite
	docker compose exec php bin/console doctrine:database:create --if-not-exists
	docker compose exec php bin/console doctrine:schema:create
	docker compose exec php bin/console doctrine:database:create --env=test --if-not-exists
	docker compose exec php bin/console doctrine:schema:create --env=test
	docker compose exec php bin/console doctrine:fixtures:load --env=dev --purge-with-truncate
	docker compose exec php bin/console cache:clear

up:      ## Start all containers
	docker compose up -d --remove-orphans

down:    ## Stop all containers
	docker compose down

restart: ## Restart all containers
	make down
	make up

composer-install: ## Install Composer dependencies
	docker compose exec php composer install

console: ## Open Symfony console
	docker compose exec php bash

cache-clear: ## Clear Symfony cache
	docker compose exec php bin/console cache:clear

check: ## Run phpstan static code analyzer
	docker compose exec php vendor/bin/phpstan clear-result-cache
	docker compose exec php vendor/bin/phpstan analyse src tests

test: ## Run all tests
	docker compose exec php bin/phpunit --testdox tests/

clear-containers: ## Delete all docker containers
	docker compose down
	docker image prune --all
	docker volume prune --all
	docker network prune

help: ## Display this help
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[36m## /[33m/'