# Laravel Auth & User Management System

A minimal Laravel application with authentication and basic user CRUD functionality.

## Features

- **Authentication System**
  - Login/Logout functionality
  - Session management
  - Protected routes

- **Dashboard**
  - Clean admin dashboard
  - Quick action links
  - User-friendly interface

- **User Management (CRUD)**
  - Create new users
  - View user list with pagination
  - Edit user details
  - Delete users
  - Soft deletes enabled

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up database:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. Build assets:
   ```bash
   npm run build
   ```

6. Start the server:
   ```bash
   php artisan serve
   ```

## Default Login

- **Email:** admin@web.com
- **Password:** 123456

## Routes

- `/` - Redirects to dashboard (if authenticated) or login
- `/login` - Login page
- `/dashboard` - Main dashboard (protected)
- `/users` - User management (protected)

## Database Structure

- **users** table with fields: id, name, email, password, contact, address, timestamps, soft deletes
- Standard Laravel auth tables (password_reset_tokens, sessions)
- Cache and jobs tables for Laravel functionality