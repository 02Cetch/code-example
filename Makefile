start:
	php -S localhost:8055 -t ./public
migrate:
	symfony console doctrine:migrations:migrate
seed:
	php bin/console doctrine:fixtures:load --group=seeds --append
