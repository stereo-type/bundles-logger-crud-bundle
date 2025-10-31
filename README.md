# Пакет для логгирования операция над сущностями на Symfony

## Руководство разработчика

### 1. Системные требования

- PHP >= 8.2
- Symfony >= 7.1

### 2. Установка

- в основном проекте в composer.json прописать
  ```
  "repositories": [
        {
            "type": "vcs",
            "url": "https://oauth2:JRB8vueyHk5JnBfUjNsb@gitlab.dev-u.ru/bundles/core_bundle.git"
        },
       {
          "type": "vcs",
          "url": "https://oauth2:JRB8vueyHk5JnBfUjNsb@gitlab.dev-u.ru/bundles/logger-crud-bundle.git"
        }
  ]
  ```
- выполнить команду `composer require slcorp/core-bundle slcorp/logger-crud-bundle`
- в конфиге проекта в `config/packages/monolog.yaml`
прописать
```
monolog:
    channels:
        - logger_crud
    handlers:
      logger_crud_bundle:
        channels: [ 'logger_crud' ]
        type: service
        id: monolog.logger_crud_bundle_handler
        level: debug
```
- после выполнения отчистить кеши `php bin/console cache:clear`
- в основном проекте появится конфиг и из бандла `core_bundle` в котором необходимо указать класс сущности пользователя, имплиментирующую интрфейс `UserInterface`
- сгенерировать миграцию, будет создана миграция для сущности `Slcorp\LoggerCrudBundle\Domain\Entity\Log` с ключами для связки с пользователем указанным в конфиге
- выполнить миграцию
- будут логироваться изменения всех сущностей в таблице `logger_crud_bundle_logs`