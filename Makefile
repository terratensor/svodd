init: docker-down-clear  \
	docker-pull docker-build docker-up \
	app-init
up: docker-up
down: docker-down
restart: down up

update-deps: app-composer-update restart

app-init: app-composer-install

app-yii-init: # инициализация yii framework
	docker-compose run --rm cli-php php init

app-composer-install:
	docker-compose run --rm cli-php composer install

app-composer-update:
	docker-compose run --rm cli-php composer update

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

