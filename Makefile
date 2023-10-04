include ./docker/.env

help: ## Show this help
	@echo "project                              build docker & start docker & install composer dependencies"
	@echo "build                                build docker"
	@echo "start                                start docker"
	@echo ""
	@echo "bash                                 open bash inside the app container"
	@echo "composer-install                     install composer dependencies"
	@echo "migrate                              apply doctrine migrations"
	@echo "seed                                 apply doctrine seeds"
	@echo "fixtures                             apply doctrine fixtures"

project:
	make build && make start && make composer-install
build:
	docker-compose -f docker/docker-compose.yml build
start:
	docker-compose  -f docker/docker-compose.yml up -d
bash:
	docker exec -it $(APP_NAME)-app /bin/bash
composer-install:
	docker exec -it $(APP_NAME)-app /bin/bash -c 'composer install'
migrate:
	docker exec -it $(APP_NAME)-app /bin/bash -c 'symfony console doctrine:migrations:migrate'
seed:
	docker exec -it $(APP_NAME)-app /bin/bash -c 'php bin/console doctrine:fixtures:load --group=seeds --append'
fixtures:
	docker exec -it $(APP_NAME)-app /bin/bash -c 'php bin/console doctrine:fixtures:load --group=common'
