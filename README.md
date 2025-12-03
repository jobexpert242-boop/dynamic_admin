# Laravel + Vue + Inertia Project Setup

This guide walks you through installing and running a Laravel project with Vue.js and Inertia.js.  
Follow the steps carefully to get your environment up and running.

---

## Prerequisites

Make sure you have the following installed:

-   PHP >= 8.1
-   Composer
-   Node.js >= 18 & npm (or Yarn)
-   MySQL/PostgreSQL (or any supported DB)
-   Git
-   Laravel installer (optional)

---

## Installation Steps

### 1. Clone the Repository

## bash

git clone https://github.com/jobexpert242-boop/dynamic_admin.git
cd your-project

# Install PHP Dependencies

## bash

composer install

# Install Node Dependencies

## bash

npm install

## or

yarn install

# Environment Setup

Copy .env.example to .env and configure your database and app settings:

## bash

cp .env.example .env

# Generate App Key

## bash

php artisan key:generate

# Run Migrations & Seeders

## bash

php artisan migrate --seed

# Build Frontend Assets

## bash

npm run dev

# for production

npm run build

# Start the Development Server

## bash

php artisan serve
