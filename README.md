bbb# Laravel + Vue + Inertia Project Setup

This guide walks you through installing and running a Laravel project with Vue.js and Inertia.js.  
Follow the steps carefully to get your environment up and running.

## Prerequisites

Make sure you have the following installed:
-   PHP >= 8.1
-   Composer
-   Node.js >= 18 & npm (or Yarn)
-   MySQL/PostgreSQL (or any supported DB)
-   Git
-   Laravel installer (optional)

## Installation Steps

### 1. Clone the Repository

git clone https://github.com/jobexpert242-boop/dynamic_admin.git
</br>
cd your-project

## Install PHP Dependencies

composer install

## Install Node Dependencies

npm install

## Environment Setup

Copy .env.example to .env and configure your database and app settings:

## bash

cp .env.example .env

## Generate App Key

php artisan key:generate

## change your database mysql and name

## Run Migrations & Seeders

php artisan migrate --seed

## Storage Link

php artisan storage:link

## and Add your larave pusher app id and key 

PUSHER_APP_ID=your app id
PUSHER_APP_KEY=your app key
PUSHER_APP_SECRET=your secret key
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY=your app key
VITE_PUSHER_APP_CLUSTER=mt1

-------

## or comment your bootstrap.js file pusher code
## and comment Notification.vue pusher code

-----

## for production

npm run build

## Build Frontend Assets

npm run dev

## Start the Development Server

php artisan serve

## or composer run dev for all start

composer run dev
