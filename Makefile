init: docker-down-clear  \
	docker-pull docker-build docker-up \
	app-init
up: docker-up
down: docker-down
restart: down up

update-deps: app-composer-update restart

app-init: app-composer-install app-wait-db app-migrations app-index-create

app-yii-init: # инициализация yii framework
	docker-compose run --rm cli-php php init

app-composer-install:
	docker-compose run --rm cli-php composer install

app-composer-update:
	docker-compose run --rm cli-php composer update

app-wait-db:
	docker-compose run --rm cli-php wait-for-it app-postgres:5432 -t 30

app-migrations:
	docker-compose run --rm cli-php php yii migrate --interactive=0

app-index-create:
	docker-compose run --rm cli-php php yii index/create --interactive=0

app-index-indexer:
	docker-compose run --rm cli-php php yii index/indexer --interactive=0

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

parse-all:
	./app/bin/fct-parser.linux.amd64 -a -j -h -o ./app/data/
#	./app/bin/fct-parser.linux.amd64 -j -h -o ./app/data/ https://фкт-алтай.рф/qa/question/view-32983

parse-current:
	./app/bin/fct-parser.linux.amd64 -j -h -o ./app/data/

indexer:
	docker-compose run --rm cli-php php yii index/indexer

update-current:
	./app/bin/fct-parser.linux.amd64 -j -h -o ./app/data/site https://фкт-алтай.рф/qa/question/view-8162
	docker-compose run --rm cli-php php yii index/update-current-comments


update-current-comments:
	docker-compose run --rm cli-php php yii index/update-current-comments
