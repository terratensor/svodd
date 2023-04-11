# fct-search

- Тут надо написать текст о проекте.

#### https://github.com/audetv/fct-search
**Если у вас есть идея или пожелание, или вы заметили какой-то баг - ошибку или неправильное поведение страниц, сервисов, \
то можно открыть новую задачу issues и написать об этом в свободной форме тут:** https://github.com/audetv/fct-search/issues \

По этой ссылке можно посмотреть список закрытых задач и обсуждение по ним:\
https://github.com/audetv/fct-search/issues?q=is%3Aissue+is%3Aclosed \
Это закрытыте [пул запросы](https://docs.github.com/ru/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests): \
https://github.com/audetv/fct-search/pulls?q=is%3Apr+is%3Aclosed

Тут можно посмореть список изменений, которые сделаны на сайте:\
https://github.com/audetv/fct-search/commits/main 


__

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

`
$ psql -d <база> -U <роль> -h <узел> -p <порт>
`

https://yiiframework.ru/forum/viewtopic.php?t=50446


`docker stack ls` \
`docker stack rm fct-search`
