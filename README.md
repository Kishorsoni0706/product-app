Products Application

Technologies Used:
Laravel 10
Vue 3
Inertia.js (Vue 3 adapter)
MySQL
Vite

Project Setup:
1. Create Laravel Project
composer create-project laravel/laravel products-app
cd products-app

2. Set Permissions
Ensure the following directories have the correct permissions:
storage
bootstrap/cache

3. Configure Environment
Create .env file if not present:
cp .env.example .env
Update the following database values in .env:
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

4. Install Breeze with Vue 3
composer require laravel/breeze --dev
php artisan breeze:install vue
npm install && npm run dev
php artisan migrate


Running the Application
Start Laravel Server
php artisan serve

Start Vite Development Server
npm run dev
