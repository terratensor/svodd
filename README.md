# svodd

Поиск по архиву вопросов и комментариев сайта ФКТ
- Тут надо написать текст о проекте.

#### https://github.com/terratensor/svodd
Если у вас есть идея или пожелание, или вы заметили какой-то баг - ошибку или неправильное поведение страниц, сервисов,
то можно [открыть новую задачу issues](https://github.com/terratensor/svodd/issues), где в свободной форме описать проблему.

Здесь находится [список закрытых задач и обсуждение по ним](https://github.com/terratensor/svodd/issues?q=is%3Aissue+is%3Aclosed)

Это [закрытые пул запросы](https://github.com/terratensor/svodd/pulls?q=is%3Apr+is%3Aclosed)

Тут можно посмотреть [список изменений](https://github.com/terratensor/svodd/commits/main), которые сделаны на сайте

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
REGISTRY=ghcr.io/terratensor IMAGE_TAG=master-1 make build
```
```
REGISTRY=ghcr.io/terratensor IMAGE_TAG=master-1 make push
```

`
$ psql -d <база> -U <роль> -h <узел> -p <порт>
`

https://yiiframework.ru/forum/viewtopic.php?t=50446


`docker stack ls` \
`docker stack rm svodd`


Таблица search_queries

```
CREATE TABLE search_queries(suggestion text, query string) index_sp='1' min_infix_len='2' index_exact_words = '1' morphology = 'stem_ru,stem_en';
```