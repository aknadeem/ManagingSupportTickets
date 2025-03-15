# 🚀 Managing Support Tickets

A Laravel-based system for managing support tickets with API functionality. This project demonstrates how to **design**, **version**, **build**, and **protect** a web API using **Laravel Sanctum**.

## 🏆 Features
- ✅ **Ticket Management**: Create and manage support tickets effortlessly.
- ✅ **Role-Based Access Control**: Different access levels for users such as Admin, Support, and Customer.
- ✅ **CRUD Operations**: Fully functional CRUD operations for managing tickets.
- ✅ **API Versioning**: Handle multiple versions of the API for better backward compatibility.
- ✅ **Rate Limiting**: Protect API from abuse using rate-limiting techniques.
- ✅ **API Authentication**: Used Sanctum for API authentication.

## ⚙️ Installation Guide

### 1. Clone the repository:
```bash
  git clone https://github.com/aknadeem/ManagingSupportTickets.git
  
  cd ManagingSupportTickets
```
### 2. Install dependencies:
First, you will need to install the PHP dependencies using Composer, and JavaScript dependencies using npm.
```bash
  composer install
  npm install && npm run dev
 ```

### 3. Configure environment variables:
Copy the .env.example file to .env and set up your database and other necessary service credentials:
```bash
  cp .env.example .env
```
Then update your .env file with the correct configuration (database, Passport/Sanctum credentials, etc.).

### 4. Generate application key:
```bash
  php artisan key:generate
```

### 5. Run migrations & Seed:
```bash
  php artisan migrate
  php artisan db:seed
```
### 6. Start Application:
```bash
  php artisan serve
```
### 7. (Optional) Configure Scribe for API Documentation:
```bash
  composer require scribe/scribe
  // add this variable in the .env file
  AUTH_USER_TOKEN=Bearer <your_token>
  php artisan scribe:generate
```
### 📄 License
This project is open-source and available under the MIT License.

