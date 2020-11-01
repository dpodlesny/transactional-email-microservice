init: docker-down-clear docker-pull docker-build docker-up app-init
app-init: app-composer-install app-wait-db app-migrations app-migrations-test
validate: app-schema-validate app-cs app-phpstan app-codeception-test
restart: docker-down docker-up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

app-composer-install:
	docker-compose run --rm transactional-email-service-php-cli composer install

app-composer-update:
	docker-compose run --rm transactional-email-service-php-cli composer update

app-wait-db:
	until docker-compose exec -T transactional-email-service-mysql /usr/bin/mysql -uroot -proot -e "SHOW DATABASES;" ; do sleep 1 ; done
	docker-compose exec -T transactional-email-service-mysql /usr/bin/mysql -uroot -proot -e "GRANT ALL ON *.* TO user@'%';FLUSH PRIVILEGES;"

app-migrations:
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:database:drop -nq --force --if-exists
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:database:create -nq
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:mi:mi -n

app-migrations-test:
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:database:drop -nq --force --if-exists --env=test
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:database:create -nq --env=test
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:mi:mi -n --env=test

app-one-migration:
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:database:drop -nq --force --if-exists
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:database:create -nq
	rm -f app/migrations/*.php
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:mi:di
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:mi:mi -n

app-schema-validate:
	docker-compose run --rm transactional-email-service-php-cli php bin/console do:schema:validate

app-cs:
	docker-compose run --rm transactional-email-service-php-cli composer cs-check

app-cs-fix:
	docker-compose run --rm transactional-email-service-php-cli composer cs-fix

app-phpstan:
	docker-compose run --rm transactional-email-service-php-cli vendor/bin/phpstan analyse src tests -l 5

app-codeception-test:
	docker-compose run --rm transactional-email-service-php-cli vendor/bin/codecept run

app-codeception-api-test:
	docker-compose run --rm transactional-email-service-php-cli vendor/bin/codecept run api

app-codeception-unit-test:
	docker-compose run --rm transactional-email-service-php-cli vendor/bin/codecept run unit
