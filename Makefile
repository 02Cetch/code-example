start:
	php -S localhost:8055 -t ./public
migrate:
	symfony console doctrine:migrations:migrate
