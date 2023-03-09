FROM nginx:1.23-alpine

LABEL org.opencontainers.image.source https://github.com/audetv/fct-search

RUN apk add --no-cache curl

COPY ./frontend/docker/common/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

COPY ./ ./

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1
