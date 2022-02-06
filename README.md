## Comando para ejecutar las migraciones:
    php artisan migrate

## Comando para ejecutar bajarse las migraciones:
    php artisan migrate:rollback

## Comando para ejecutar los seeders:
    php artisan db:seed

## Comando para crear un factory:
    php artisan make:factory NameFactory

## Comando para crear un seeder:
    php artisan make:seeder NameSeeder

## Comando para crear una nueva validacion:
    php artisan make:middleware Validations\\Requests\\NameValidation\\Action

## Comando para crear un nuevo controlador:
    php artisan make:controller NameController\\Action --invokable

## Comando para crear un test:
    php artisan make:test v1\\name\\ActionTest
