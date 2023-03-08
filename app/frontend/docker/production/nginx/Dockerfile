FROM nginx:1.23-alpine

LABEL org.opencontainers.image.source https://github.com/audetv/fct-search

RUN apk add --no-cache curl

COPY ./docker/common/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

COPY ./web ./web
