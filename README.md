#Avant School Management System - Laravel Backend

Laravel backend for Avant School Management System.

## Prequisites
 - Ubuntu 20.04 LTS: Other Linux distros will do fine. However this was developed and tested on Ubuntu 20.04 LTS.
 - PHP 8.0: Other PHP should work. 
 - PHP extensions: php-bcmath, php-curl, php-gd, php-mbstring, php-pgsql, php-redis, php-xml, php-zip
 - PostgreSQL 13: Other RDBMS like MYSQL may require some changes to the database migration files.
 - REDIS: Cache server.
 - Supervisor: for running Laravel Horizon jobs.

## Underlying Technology
 - This is build on Laravel 8.5 as the base.
 - Uses Laravel Horizon for queue jobs.
 - spatie/laravel-permission for managing roles and permissions.
 - maatwebsite/excel for excel exports & imports.
 - barryvdh/laravel-dompdf for pdf exports.
 - Uses Laravel Sanctum for authentications.
