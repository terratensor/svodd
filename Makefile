init: docker-down \
	app-clear \
	docker-pull docker-build docker-up \
	app-init
up: docker-up
down: docker-down
restart: down up

update-deps: app-composer-update restart

app-clear:
	docker run --rm -v ${PWD}/app:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'
	docker run --rm -v ${PWD}/app:/app -w /app alpine sh -c 'rm -rf frontend/runtime/cache/* frontend/runtime/cache/*'

app-init: app-permissions app-composer-install app-wait-db app-yii-init \
	app-migrations app-console-run \
#	app-index-create app-index-indexer

app-permissions:
	docker run --rm -v ${PWD}/app:/app -w /app alpine chmod 777 var/cache var/log var/test

app-yii-init: # инициализация yii framework
	docker-compose run --rm cli-php php init-actions --interactive=0

app-composer-install:
	docker-compose run --rm cli-php composer install

app-composer-update:
	docker-compose run --rm cli-php composer update

app-wait-db:
	docker-compose run --rm cli-php wait-for-it app-postgres:5432 -t 30

app-console-run:
	docker-compose run --rm cli-php php yii rules/bootstrap

app-migrations:
	docker compose run --rm cli-php php yii migrate --interactive=0
	docker compose run --rm cli-php php yii migrate-rbac --interactive=0

app-index-create:
	docker compose run --rm cli-php php yii index/create --interactive=0

app-index-indexer:
	docker compose run --rm cli-php php yii index/indexer --interactive=0

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	- docker-compose pull

docker-build:
	DOCKER_BUILDKIT=1 COMPOSE_DOCKER_CLI_BUILD=1 docker-compose build --build-arg BUILDKIT_INLINE_CACHE=1 --pull

push-dev-cache:
	docker-compose push

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

build: build-frontend build-cli-php

build-frontend:
	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
        --cache-from ${REGISTRY}/fct-search-frontend:cache \
        --tag ${REGISTRY}/fct-search-frontend:cache \
        --tag ${REGISTRY}/fct-search-frontend:${IMAGE_TAG} \
        --file app/frontend/docker/production/nginx/Dockerfile app

build-cli-php:
	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
		--target builder \
		--cache-from ${REGISTRY}/fct-search-cli-php:cache-builder \
		--tag ${REGISTRY}/fct-search-cli-php:cache-builder \
		--file app/console/docker/production/php-cli/Dockerfile app

	DOCKER_BUILDKIT=1 docker --log-level=debug build --pull --build-arg BUILDKIT_INLINE_CACHE=1 \
    	--cache-from ${REGISTRY}/fct-search-cli-php:cache-builder \
    	--cache-from ${REGISTRY}/fct-search-cli-php:cache \
    	--tag ${REGISTRY}/fct-search-cli-php:cache \
    	--tag ${REGISTRY}/fct-search-cli-php:${IMAGE_TAG} \
    	--file app/console/docker/production/php-cli/Dockerfile app

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push-build-cache: push-build-cache-frontend push-build-cache-cli-php

push-build-cache-frontend:
	docker push ${REGISTRY}/fct-search-frontend:cache

push-build-cache-cli-php:
	docker push ${REGISTRY}/fct-search-cli-php:cache-builder
	docker push ${REGISTRY}/fct-search-cli-php:cache

push:
	docker push ${REGISTRY}/fct-search-frontend:${IMAGE_TAG}
	docker push ${REGISTRY}/fct-search-cli-php:${IMAGE_TAG}

deploy:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker network create --driver=overlay traefik-public || true'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER} && mkdir site_${BUILD_NUMBER}'

	envsubst < docker-compose-production.yml > docker-compose-production-env.yml
	scp -o StrictHostKeyChecking=no -P ${PORT} docker-compose-production-env.yml deploy@${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
	rm -f docker-compose-production-env.yml

	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}/secrets'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cp .secrets/* site_${BUILD_NUMBER}/secrets'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker service rm fct-search_current-topic-parser || true'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker service rm fct-search_app-updater || true'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml fct-search --with-registry-auth --prune'

deploy-clean:
	rm -f docker-compose-production-env.yml

rollback:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml fct-search --with-registry-auth --prune'
