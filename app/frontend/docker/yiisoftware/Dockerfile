FROM yiisoftware/yii2-php:8.3-fpm-nginx

COPY ./yiisoftware/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1
