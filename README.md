# 🏡 Property Booking API – Laravel 11

This is a role-based property booking REST API built using **Laravel 11**, with user authentication powered by **Laravel Sanctum**, admin-only resource management, and OpenAPI documentation using **Swagger**.

---

## 🚀 Features

- **JWT-authenticated API** using Laravel Sanctum
- **Role-based access control**
    - `guest`: Can view properties and create bookings
    - `admin`: Full access to create/update/delete properties, manage availability & view bookings
- **Property CRUD**
- **Booking creation**
- **Availability management**
- **Swagger API Docs**

---

## 🧑‍💻 Tech Stack

- Laravel 11
- Sanctum (API token auth)
- Eloquent ORM
- Repository & Service pattern
- Swagger via `darkaonline/l5-swagger`
- Role middleware

---

## 📦 Installation

```bash
git clone https://github.com/your-repo/property-booking-api.git
cd property-booking-api

# Install dependencies
composer install

# Create env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Serve
php artisan serve
