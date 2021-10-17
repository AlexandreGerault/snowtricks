.PHONY: start
start:
	docker-compose up -d

.PHONY: stop
stop:
	docker-compose down

.PHONY: format
format:
	docker-compose exec php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src && docker-compose exec php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix domain && docker-compose exec php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix tests


.PHONY: analyse
analyse:
	docker-compose exec php ./vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: test
test:
	docker-compose run php ./vendor/bin/phpunit
