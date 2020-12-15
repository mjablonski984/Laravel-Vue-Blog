# Laravel & Vue Blog

Laravel and Vue blog with admin panel allowing to :
- create, read, update and delete posts,
- create, read, update and delete categories and tags,
- manage user roles and permissions,


## Setup

1. Install Composer Dependencies:
```
composer install
```
2. Install NPM Dependencies:
```
npm install
```
3. Create a copy of .env file (copy of env.example):
```
cp .env.example .env
```
4. Generate an app encryption key:
```
php artisan key:generate
```
5. Create a new database for the application.

6. In the .env file, add database information to allow Laravel to connect to the database. 
In .env file fill in the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to match the credentials of the database you just created.

7. Migrate the database:
```
php artisan migrate
```

8. Seed the database:
```
php artisan db:seed --class=DatabaseSeeder
```