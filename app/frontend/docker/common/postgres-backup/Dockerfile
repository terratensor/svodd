FROM alpine:3.20

RUN apk add --no-cache python3 py3-pip bash coreutils postgresql-client \
    && pip3 install --upgrade certifi --break-system-packages \
    && pip3 install --upgrade awscli --break-system-packages

COPY ./wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

COPY ./postgres-backup/backup.sh /usr/local/bin/backup
RUN chmod 555 /usr/local/bin/backup

WORKDIR /app

CMD ["backup"]
