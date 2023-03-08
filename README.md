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
