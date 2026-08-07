# User Management Laravel App

A Laravel 12 application for web-based user management and a secured REST API.

# requirement

- Minimun PHP 8.2

## Features

- Web registration and login with session-based auth
- Web user CRUD (create, read, update, delete)
- API registration, login, logout, and user CRUD
- API protected by Laravel Sanctum token authentication
- Validation using `FormRequest` classes
- Passwords hashed with `Hash::make`
- Seeded admin user for development

## Installation

1. Copy environment file:

   ```bash
   cp .env.example .env
   ```

2. Configure database settings in `.env`
3. Install PHP dependencies:

   ```bash
   composer install
   ```

4. Generate application key:

   ```bash
   php artisan key:generate
   ```

5. Run migrations and seed the database:

   ```bash
   php artisan migrate --seed
   ```

## Seeded User

A default user is created in `database/seeders/DatabaseSeeder.php`:

- Email: `it@rechmand.id`
- Password: `Rechmand@2026!`

## Web Usage

- `/register` : register new user
- `/login` : login page
- `/users` : user list (requires login)
- `/users/create` : add user
- `/users/{id}/edit` : edit user

## API Endpoints

Public:

- `POST /api/register`
- `POST /api/login`

Protected (`Authorization: Bearer <token>`):

- `POST /api/logout`
- `GET /api/users`
- `GET /api/users/{id}`
- `POST /api/users`
- `PUT /api/users/{id}`
- `DELETE /api/users/{id}`

## Validation Rules

- `email`: required, valid email, max 255, unique
- `name`: required, starts with letter, letters and spaces only, max 100
- `password`: required for create, minimum 6, contains lowercase, uppercase, number, special character

## Notes

- `.env` is excluded via `.gitignore`
- API uses Sanctum token auth and does not return passwords
- Web auth uses custom session middleware defined in `bootstrap/app.php`

## Run Development Server

```bash
php artisan serve
```
