cache-clear:
	 php bin/console cache:clear

stan:
	php vendor/bin/phpstan analyse src tests

fix:
	vendor/bin/php-cs-fixer fix src && vendor/bin/php-cs-fixer fix tests

entity:
	php bin/console m:e

migration:
	php bin/console make:migration

migration-migrate:
	php bin/console doctrine:migrations:migrate

jwt:
	php bin/console lexik:jwt:generate-keypair --skip-if-exists
