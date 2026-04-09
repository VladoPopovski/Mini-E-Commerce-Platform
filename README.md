# Mini E-Commerce Platform

A full-featured, role-based e-commerce platform built with **Laravel**, **Livewire**, and **Laravel Fortify** — supporting buyers, vendors, and admins with secure authentication, two-factor auth, cart management, and order processing.

## Author

###  Vlado Popovski

---

## DB sketch

![DBsketch.png](DBsketch.png)


---

## Features

- **Authentication** — Login, registration, and logout via Laravel Fortify
- **Email Verification** — Required before accessing protected routes
- **Two-Factor Authentication (2FA)** — TOTP-based with confirmation flow
- **Password Reset** — Full forgot/reset password flow
- **Role-Based Access Control** — `buyer`, `vendor`, and `admin` roles
- **Cart System** — Add, update, and clear cart items
- **Checkout** — Stock validation, total limits, and order creation
- **Order Management** — Forward-only status transitions
- **Vendor Dashboard** — Vendor-only routes and product management
- **Profile Settings** — Update name, email, and password
- **ULID Primary Keys** — Collision-resistant, sortable IDs throughout

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Frontend | Livewire 3 |
| Auth | Laravel Fortify |
| Database | MySQL (SQLite for testing) |
| IDs | ULID (`Str::ulid()`) |
| Testing | PestPHP / PHPUnit |

---

## Requirements

- PHP **>= 8.2**
- Composer **>= 2.x**
- Node.js **>= 18.x** & npm
- MySQL **>= 8.0** (or compatible)

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/VladoPopovski/Mini-E-Commerce-Platform.git
cd mini-ecommerce-platform
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies and build assets

```bash
npm install
npm run build
```

### 4. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure your database

Open `.env` and update:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_ecommerce
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 7. Start the development server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000)

---

## Default Roles

| Role | Description |
|---|---|
| `buyer` | Default role on registration. Can browse, add to cart, and place orders. |
| `vendor` | Can manage products and view orders for their store. |
| `admin` | Full access to all routes and features. |

> When registering via the UI, select a role. When registering via the API or tests, `buyer` is assigned by default.

---

## Running Tests

The test suite uses an in-memory SQLite database — no extra setup needed.

```bash
php artisan test
```

To run a specific test file:

```bash
php artisan test tests/Feature/CheckoutTest.php
```

To run with coverage (requires Xdebug or PCOV):

```bash
php artisan test --coverage
```

Test suites covered:

- Authentication (login, logout, 2FA)
- Email Verification
- Password Reset
- Registration
- Two-Factor Challenge
- Checkout (stock, total limits, role guards)
- Order Status Transitions
- Profile & Security Settings
- Dashboard Access

---

## Project Structure

```
app/
├── Actions/Fortify/        # Fortify action classes (register, reset password)
├── Concerns/               # Shared traits (validation rules)
├── Enums/                  # UserRole enum
├── Http/Controllers/       # Standard controllers
├── Livewire/               # Livewire components (auth, settings, checkout)
├── Models/                 # Eloquent models (User, Vendor, Cart, Order...)
├── Providers/              # Service providers including FortifyServiceProvider
database/
├── factories/              # Model factories with ULID support
├── migrations/             # All schema migrations
├── seeders/                # Database seeders
tests/
├── Feature/                # Feature tests
└── Unit/                   # Unit tests
```

---

## Key Configuration

### Two-Factor Authentication

Enabled in `config/fortify.php`:

```php
Features::twoFactorAuthentication([
    'confirm' => true,
    'confirmPassword' => true,
]),
```

### Password Reset URL

Configured in `FortifyServiceProvider` to correctly build reset links:

```php
ResetPassword::createUrlUsing(function ($user, string $token) {
    return url(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ], false));
});
```

---

## Notes

- All primary keys are **ULIDs** — ensure any code accepting user IDs uses `string|int|null` type hints rather than `int`.
- The `role` field defaults to `buyer` if not provided during registration.
- Two-factor columns (`two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`) are required in the `users` table.

---
