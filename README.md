## Test tasks project

Для запуска выполняем всё по порядку:
<br><br>
```composer install``` - устанавливаем зависимости
<br><br>
Переименовываем файл ".env.example" на просто ".env" и заменяем данные БД на актуальные
<br><br>
```php artisan key:generate``` - генерируем APP_KEY
<br><br>
```php artisan migrate``` - мигрируем таблицы
<br><br>
```npm i``` - устанавливаем пакеты npm
<br><br>
```php artisan serve``` - запускаем локальный сервер
