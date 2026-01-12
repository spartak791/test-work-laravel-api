# Тестовое задание. Простое API на Laravel

[Постановка задачи](task-description.pdf)  
[Описание OpenAPI](openapi.yaml)  

Base URL: http://localhost/api  

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
