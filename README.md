1. Penjelasan Project
 
Proyek ini adalah sebuah sistem API untuk mengelola pengguna (users), gender (genders), dan mahasiswa (students). Hubungan antara tabel genders dan students adalah relasi one-to-many, di mana satu gender bisa dimiliki oleh banyak mahasiswa. Sistem ini dilengkapi dengan autentikasi menggunakan Laravel Sanctum untuk mengamankan endpoint yang hanya bisa diakses oleh pengguna yang sudah terdaftar dan login.

Fitur API

-> Autentikasi Pengguna: Sistem menggunakan Laravel Sanctum untuk mengelola otentikasi pengguna, yang memungkinkan pengguna untuk mendaftar, login, dan logout.

-> Pengelolaan Gender: CRUD (Create, Read, Update, Delete) untuk entitas gender. Hanya pengguna yang sudah login yang bisa mengakses fitur ini.

-> Pengelolaan Mahasiswa: CRUD untuk entitas mahasiswa, yang memiliki relasi dengan gender. Hanya pengguna yang sudah login yang bisa mengakses fitur ini.

2. Desain Database

https://app.diagrams.net/#G1vSUrZ9N1z39mcSJSC3cd0nu5tJxmaVvI#%7B%22pageId%22%3A%22QR20fnRkiEpbkgz4P17Y%22%7D 

3. Dependency

->Laravel 8

-> MySQ

-> PHP 8.2

4. Install and Run Project

-> composer install

-> php artisan key:generate

-> untuk database, import ke mysql database yang ada didalam folder bernama dot-backend.sql

-> Run project : php artisan serve




