# Starter Kits, Breeze, and Middleware
Laravel provides starter kits like Laravel Breeze to quickly scaffold common authentication features such as registration, login, password reset, and profile management.

## Using Laravel Breeze Starter Kit - Pre Laravel 12
You can scaffold a new Laravel project with Breeze by running:

```
laravel new app
```

and selecting Breeze as the starter kit during setup. Breeze assumes a fresh project and will overwrite some files like routes, views, and components.

Breeze supports multiple frontend stacks including React, Vue, Livewire, or traditional Blade with JavaScript.

## Using Laravel Breeze Starter Kit - Laravel 12
Since Laravel 12 the options have changed during the setup process of a new project. To setup a new project with breeze scaffolding create a new project in Herd or run:
```
laravel new app
```

Choose no starter kit and let Laravel build the project. When Laravel has finished run:
```
composer require laravel/breeze --dev
```

Once the required packages have been acquired run:
```
php artisan breeze:install
```

Once composer has finished it's advised to run migrations and npm i && npm run dev.

After installation, run the app and you’ll see login and register links.

## Features Included
* Registration and login forms
* Dashboard accessible only to authenticated users
* Profile editing and password update
* Logout functionality
* Middleware to protect routes and redirect guests to login

## Authentication in Breeze
Routes are protected by middleware like `auth` and `verified` to ensure only signed-in and verified users can access certain pages.
The authenticated user can be accessed via the `Auth::` facade or helper.
Breeze uses Blade components extensively for layouts, inputs, labels, and validation errors.
Registration logic includes validation, password hashing, event firing, and automatic login.

## Middleware Explained
Middleware acts as layers that process requests before reaching your application logic. For example, the auth middleware checks if a user is signed in and redirects to login if not.