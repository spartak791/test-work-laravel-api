# Тестовое задание. Простое API на Laravel

[Постановка задачи](task-description.pdf)  
[Описание OpenAPI](openapi.yaml)  

API base URL: http://localhost/api  

Установка зависимостей:
```shell
composer install
```
Билд образов docker:
```shell
./vendor/bin/sail build --no-cache
```
Поднять окружение (docker compose):
```shell
./vendor/bin/sail up -d
```
Накатить миграции с тестовыми данными:
```shell
./vendor/bin/sail artisan migrate --seed
```
Положить окружение:
```shell
./vendor/bin/sail down
```
