```shell
composer install
cp .env.example .env
php artisan key:generate
```

В `.env` указать информацию о БД (`DB_*`)

```shell
php artisan migrate
```
