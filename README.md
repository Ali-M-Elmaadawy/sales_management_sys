# Project README

## Overview

This is a full-stack web application built using:

* Laravel (Backend API)
* Vue.js (Frontend)
* MySQL Database

The project provides product management features including add, edit, delete, and analytics such as low-selling product insights.

---

## Requirements

Before running the project, make sure you have installed:

* PHP >= 8.1
* Composer
* Node.js >= 16
* NPM or Yarn
* MySQL

---

## Installation Steps

### 1. Clone the project

```bash
git clone <repository-url>
cd project-folder
```

---

### 2. Install Laravel dependencies

```bash
composer install
```

---

### 3. Install Vue dependencies

```bash
npm install
```

---

### 4. Setup environment file

```bash
cp .env.example .env
```

Then update `.env` with your database credentials:

```
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

### 5. Generate application key

```bash
php artisan key:generate
```

---

### 6. Run database migrations

```bash
php artisan migrate
```

---

### 7. Create storage link

```bash
php artisan storage:link
```

This will create a symbolic link between `storage/app/public` and `public/storage` so uploaded images can be accessible from the browser.

---

### 8. (Optional) Run seeders

If the project includes sample data:

```bash
php artisan db:seed
```

Or run both migration + seeding:

```bash
php artisan migrate --seed
```

---

### 9. Build frontend assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

### 10. Run the development server

```bash
php artisan serve
```

The project will be available at:

```
http://127.0.0.1:8000
```

---

## API & Frontend Notes

* Backend runs on Laravel API routes
* Frontend is built using Vue components
* Axios is used for API communication

---

## Common Commands

```bash
php artisan migrate:fresh --seed   # Reset database and seed
php artisan cache:clear            # Clear cache
php artisan config:clear           # Clear config cache
npm run dev                        # Start frontend dev server
```

---

## Troubleshooting

If something goes wrong:

* Check `.env` configuration
* Run `composer install` again
* Run `npm install` again
* Run `php artisan storage:link`
* Clear cache using artisan commands

---

## Author

Developed as a Laravel + Vue full-stack project with AI-assisted development for analysis and feature building.
