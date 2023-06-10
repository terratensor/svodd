FROM yiisoftware/yii2-php:8.1-fpm-nginx

LABEL org.opencontainers.image.source https://github.com/terratensor/svodd

COPY ./frontend/docker/production/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

COPY ./ ./

RUN chown -R 33:33 /app/var
RUN chown -R 33:33 /app/frontend/runtime
RUN chown -R 33:33 /app/frontend/web/assets
RUN chown -R 33:33 /app/console/runtime

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1
