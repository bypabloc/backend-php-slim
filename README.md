## Comando para ejecutar las migraciones:
    php artisan migrate

## Comando para ejecutar bajarse las migraciones:
    php artisan migrate:rollback

## Comando para ejecutar los seeders:
    php artisan db:seed

## Comando para ver las rutas:
    php artisan route:list --path='api/v1'

## Comando para crear un factory:
    php artisan make:factory NameFactory
    
## Comando para crear un seeder:
    php artisan make:seeder NameSeeder
    
## Comando para crear una nueva validacion:
    php artisan make:middleware Validations\\Requests\\NameValidation\\Action

## Comando para crear un nuevo controlador:
    php artisan make:controller NameController\\Action --invokable

## Comando para crear un nuevo modelo:
    php artisan make:model NameModel

## Comando para crear un test:
    php artisan make:test v1\\name\\ActionTest

## Comando para ejecutar los tests:
    php artisan test 
## Se recomienda usar la bandera --stop-on-failure para que no se ejecuten los demas tests si uno falla:
    --stop-on-failure
## Para filtrar test a uno o unos en especifico puede agregar la bandera:
    --filter=FindOneTest

## Para usar plantilla para los commits:
    git config --global commit.template .gitmessage

## para instalar laravel telescope:
    php artisan telescope:install

## y luego ejecutar las migraciones
    php artisan migrate

## para ver los eventos de telescope ir a la ruta: "/telescope"
