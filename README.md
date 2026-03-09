## LaraWithVue

This project is a modern e-commerce web application built with Laravel (PHP) and Vue.js. It features a robust backend, a dynamic frontend, and seamless integration between the two using Inertia.js.

### Features

- **Backend:**
  - Laravel 12, PHP 8.2
  - Inertia.js for SPA integration
  - Stripe payment integration
  - User authentication and profile management
  - Role and permission management (Spatie)
  - Country/state management
  - Order, cart, product, shipping rate models
- **Frontend:**
  - Vue 3, Pinia state management
  - Vue Router
  - PrimeVue UI components
  - Bootstrap and Tailwind CSS
  - SweetAlert2, Toastr notifications
  - Stripe payment components
- **Build Tools:**
  - Vite, Tailwind, PostCSS
  - Laravel Vite plugin
- **Dev Tools:**
  - Laravel Breeze, PHPUnit, Faker, Pint, Sail

### Project Structure

- `app/Models/` — Eloquent models for User, Product, Order, OrderItems, CartProduct, Country, ShippingRates
- `resources/js/` — Vue.js SPA, pages, components
- `routes/` — Laravel route definitions (web, api, auth)
- `config/` — Laravel configuration files
- `database/` — Migrations, seeders, factories
- `public/` — Public assets and entry point

### Setup Instructions

1. **Clone the repository**
2. **Install PHP dependencies:**
	```bash
	composer install
	```
3. **Install Node.js dependencies:**
	```bash
	npm install
	```
4. **Configure environment:**
	- Copy `.env.example` to `.env` and update settings
	- Set up database credentials
5. **Run migrations and seeders:**
	```bash
	php artisan migrate --seed
	```
6. **Build frontend assets:**
	```bash
	npm run dev
	```
7. **Start the Laravel server:**
	```bash
	php artisan serve
	```

### Usage

- Access the app at [http://localhost:8000](http://localhost:8000)
- Register/login, manage profile, browse products, add to cart, checkout with Stripe
- Admin features: manage orders, products, shipping rates

### License

MIT
