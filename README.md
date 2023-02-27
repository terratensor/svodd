# fct-search

#### Подключение PostgreSQL для разработки
app/common/config/main-local.php
```
'db' => [
    'class' => \yii\db\Connection::class,
    'dsn' => 'pgsql:host=app-postgres;dbname=app',
    'username' => 'app',
    'password' => 'secret',
    'charset' => 'utf8',
],
```
