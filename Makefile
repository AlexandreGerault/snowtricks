.PHONY: install
install: prepare-install migrate build-front

.PHONY: prepare-install
prepare-install:
	cp .env.example .env
	docker compose build
	docker compose up -d
	cp apps/symfony/.env.example apps/symfony/.env
	docker compose run php composer install

.PHONY: migrate
migrate:
	docker compose run php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: build-front
build-front:
	docker compose run -w="/usr/src/app" -u=1000:1000 node yarn
	docker compose run -w="/usr/src/app" -u=1000:1000 node yarn build

.PHONY: start
start:
	docker compose up -d

.PHONY: stop
stop:
	docker compose down

.PHONY: format
format:
	docker compose run php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix

.PHONY: analyse
analyse:
	docker compose run php ./vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: build-test
build-test:
	docker compose -f docker compose.test.yml --env-file=.env.test build

.PHONY: test
test:
	docker compose -f docker compose.test.yml --env-file=.env.test run --rm php_test bin/phpunit --colors

