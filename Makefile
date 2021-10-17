.PHONY: start
start:
	docker-compose up -d

.PHONY: stop
stop:
	docker-compose down

.PHONY: format
format:
	docker-compose run php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix

.PHONY: analyse
analyse:
	docker-compose run php ./vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: build-test
build-test:
	docker-compose -f docker-compose.test.yml --env-file=.env.test build

.PHONY: test
test:
	docker-compose -f docker-compose.test.yml --env-file=.env.test run --rm php_test bin/console d:s:u --force
	docker-compose -f docker-compose.test.yml --env-file=.env.test run --rm php_test vendor/bin/phpunit
