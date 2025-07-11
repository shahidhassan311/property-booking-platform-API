# 🏡 Property Booking API

This is a role-based property booking REST API built using **Laravel 11**, featuring modern architecture, JWT-style authentication using **Laravel Sanctum**, background job processing with **Laravel Queues**, and clean documentation via **Swagger (OpenAPI)**.

---

## 🚀 Features

- **Sanctum-based API Token Authentication**
- **Role-based Access Control**
  - `guest`: Can browse properties and make bookings
  - `admin`: Can manage properties, availability, and bookings
- **Property Management** (CRUD)
- **Booking Functionality**
- **Availability Slots**
- **Background Queue Jobs for heavy operations**
- **Swagger Documentation**

---

## 🧑‍💻 Tech Stack

| Layer | Technology |
|-------|------------|
| Framework | Laravel 11 |
| API Auth | Laravel Sanctum |
| Roles & Permissions | Custom role middleware |
| ORM | Eloquent |
| Architecture | Repository & Service pattern |
| Design Pattern | Singleton |
| Background Processing | Laravel Queue (Database driver) |
| Queue Management | Jobs + `php artisan queue:work` |
| API Documentation | Swagger (`darkaonline/l5-swagger`) |

---

## 🔄 Queue System Setup

```env
QUEUE_CONNECTION=database
````

### Step 2: Run required migration

```bash
php artisan queue:table
php artisan migrate
```

### Step 3: Dispatch job (example)

```php
YourHeavyJob::dispatch();
```

### Step 4: Start the queue worker

```bash
php artisan queue:work
```

> Use `--daemon` in production and monitor with **Supervisor**

---

## 📦 Installation

```bash
git clone https://github.com/shahidhassan311/property-booking-platform-API.git
cd property-booking-platform-API

# Install dependencies
composer install

# Create env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Set database credentials in .env

# Run migrations
php artisan migrate

# Run seeder
php artisan db:seed

# Run Swagger docs (optional)
php artisan l5-swagger:generate

# Serve the application
php artisan serve
```

---

## 📘 API Documentation

Access Swagger UI at:

```
http://localhost:8000/api/documentation
```
