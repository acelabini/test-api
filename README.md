## Installation

### Prerequisite
1. PHP 8
2. Composer
3. WSL for <a href="https://laravel.com/docs/9.x/sail#introduction">Laravel sail</a>
4. Learn about <a href="https://laravel.com/docs/9.x/sail#introduction">Laravel sail</a>

### Steps
1. Clone repo
2. Copy .env.example, run `cp .env.example .env`
3. cd inside the project
4. Run `composer install`
5. Run `./vendor/bin/sail up`
6. Create database inside the mysql docker image
    1. Run `docker ps | grep mysql` to get the container ID of mysql:8.0 image
    2. Copy the hash/container id of mysql:8.0 docker image
    3. To be able to run the command inside the specified image, run `docker exec -it [paste the hash here] bash`
    4. Once you are inside the mysql:8.0 image, run `mysql`
    5. Create database by running `CREATE DATABASE byldan_developments_api;`
    6. Run a new terminal, then continue to the next step
7. Check if meilisearch was successfully installed http://localhost:7700 or http://meilisearch:7700
8. Run migration `./vendor/bin/sail artisan migrate`
9. Run seeder `./vendor/bin/sail artisan db:seed`
10. Run `./vendor/bin/sail artisan init:meili_settings App\Models\Project`
11. Generate swagger `./vendor/bin/sail artisan l5-swagger:generate`

### URLs
* http://localhost/api/documentation/

### Autocomplete/Intellisense
* Please refer to this: https://github.com/barryvdh/laravel-ide-helper

### FAQ
* I've created a new route, but it doesn't appear:
  * Just run `php artisan route:clear` inside docker
