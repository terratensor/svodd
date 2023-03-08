FROM nginx:1.23-alpine

LABEL org.opencontainers.image.source https://github.com/audetv/fct-search

RUN apk add --no-cache curl

COPY ./frontend/docker/common/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

COPY ./frontend/web ./frontend/web
