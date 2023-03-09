# fct-search

#### Подключение PostgreSQL для разработки
app/common/config/main-local.php
```

$dbname = getenv('POSTGRES_DB');
$dbhost = getenv('POSTGRES_HOST');
...
 'db' => [
    'class' => \yii\db\Connection::class,
    'dsn' => "pgsql:host=$dbhost;dbname=$dbname",
    'username' => getenv('POSTGRES_USER'),
    'password' => trim(file_get_contents(getenv('POSTGRES_PASSWORD_FILE'))),
    'charset' => 'utf8',
],
```

### Deploy
https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry
- $ export CR_PAT=YOUR_TOKEN
  Using the CLI for your container type, sign in to the Container registry service at ghcr.io.

- $ echo $CR_PAT | docker login ghcr.io -u USERNAME --password-stdin
> Login Succeeded

-----

```
cd provisioning
```
```
make authorize
```
```
make docker-login 
```
```
cd ..
```
```
REGISTRY=ghcr.io/audetv IMAGE_TAG=master-1 make build
```
```
REGISTRY=ghcr.io/audetv IMAGE_TAG=master-1 make push
```
```
HOST=45.131.41.158 PORT=22 REGISTRY=ghcr.io/audetv IMAGE_TAG=master-1 BUILD_NUMBER=1 APP_DB_PASSWORD_FILE=docker/development/secrets/app_db_password APP_MAILER_PASSWORD_FILE=docker/development/secrets/app_mailer_password SENTRY_DSN_FILE=docker/development/secrets/sentry_dsn APP_MAILER_HOST=smtp.mail.ru APP_MAILER_PORT=1025 APP_MAILER_USERNAME=app APP_MAILER_FROM_EMAIL=support@audetv.ru make deploy
```

`
$ psql -d <база> -U <роль> -h <узел> -p <порт>
`

https://yiiframework.ru/forum/viewtopic.php?t=50446
