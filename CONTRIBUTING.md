# How to contribute

## Installation

`git@github.com:RikuKukkaniemi/speed-typer-frontend.git`

After cloning the project

1. Create `.env` file
2. Copy `.env.example` content to `.env` file
3. Start project using sail: https://laravel.com/docs/8.x/sail or
    - `composer install`
    - `npm install`
    - `npm run watch`
    - `php artisan serve`

## Create new Docker image

`docker build tag speed-typer-frontend:{VERSION.NUMBER} .`