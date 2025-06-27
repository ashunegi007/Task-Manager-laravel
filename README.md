# Laravel Task Manager

A simple task manager built with Laravel, Blade, and Laravel Breeze. Features CRUD and image upload.

## Features
- Task CRUD (Create, Read, Update, Delete)
- Image upload with preview
- MySQL support

## Setup Instructions
```bash
git clone https://github.com/ashunegi007/Task-Manager-laravel.git
cd task-manager
composer install
cp .env.example .env
php artisan key:generate

# Set DB in .env
touch database/laravel11crud.sql
php artisan migrate

php artisan serve