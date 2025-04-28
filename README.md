# 🛒 ClicknBuy

**ClicknBuy** is a custom-built e-commerce web application developed with **PHP**, designed for seamless online shopping of products and motorcycles.  
It features **user authentication**, **shopping cart**, **test ride requests**, and a **full admin dashboard** for managing products, orders, and users.  
Built on a **custom MVC-like framework** with a file-based router, **session-based authentication**, and a **MySQL database**.

---

## 📑 Table of Contents
- [Features](#-features)
- [Technologies](#-technologies)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Routes](#-routes)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features
- **User Authentication:** Register, login, logout, and profile management with role-based access (User, Admin, Superuser).
- **Shopping Cart:** Add, update, remove items, and checkout; guest carts merge on login.
- **Product Management:** Browse and manage products and motorcycles (Admin CRUD).
- **Test Ride Requests:** Request and manage motorcycle test rides.
- **Admin Dashboard:** View metrics, export earnings, manage users, products, and orders.
- **Role-Based Middleware:** Restrict routes by user roles.
- **Session-Based Cart:** Guest users can add to cart without an account.

---

## ⚙️ Technologies
- **PHP 7.4+** – Backend logic
- **MySQL** – Database
- **Composer** – Dependency management (`vlucas/phpdotenv`)
- **Bootstrap** – Frontend styling
- **SB Admin 2** – Admin dashboard template
- **JavaScript** – Client-side interactivity

---

## 📥 Installation

### Prerequisites
- PHP >= 7.4
- MySQL
- Composer
- Web server (e.g., Apache via Laragon)
- Node.js (optional for asset compilation)

### Steps

1. **Clone the repository:**
   ```bash
   git clone <https://github.com/kssorvan/clicknbuy.git> clicknbuy
   cd clicknbuy
2.Install dependencies:

bash
Copy
Edit
composer install
Set up environment:

bash
Copy
Edit
cp .env.example .env
Update .env file:

env
Copy
Edit
APP_ENV=development
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clicknbuy
DB_USERNAME=root
DB_PASSWORD=
Create database:

Create a MySQL database clicknbuy.

Import SQL scripts if available.

Configure web server:

Point web server to public/ folder.

Enable mod_rewrite for clean URLs.

Start application:

Visit http://localhost/clicknbuy.

⚙️ Configuration
Database connection: Managed via .env or Core/Database.php.

Sessions: Configured in public/index.php.

Logging: Errors recorded at logs/error.log.

Static assets: Should be available under public/asset/.

🚀 Usage
Access
Visit http://localhost/clicknbuy

Default User Accounts

Role	Email	Password
Admin	admin@example.com	admin123
Superuser	superuser@example.com	super123
User	user@example.com	user123
Key Features
Users: Browse products, request test rides, manage cart, checkout.

Admins: Manage products, orders, categories, motorcycles, and earnings.

Superusers: Manage users (update, delete).

🛣 Routes
Public
GET / — Home page

GET /products — Browse products

GET /product/{id} — View product

GET /motorcycles — Browse motorcycles

GET /motorcycle/{id} — View motorcycle

Guests
GET|POST /login

GET|POST /registration

Authenticated Users
GET /profile

GET /cart

POST /addcart/{id}

POST /cart/checkout

GET /test-ride/request/{id}

Admin
/dashboard

/tbproducts

/tbcategories

/tborders

/motorcycles/admin

/brands

/test-rides/admin

Superuser
/tbusers/update

/tbusers/delete



📜 License
This project is licensed under the MIT License.
See the LICENSE file for full details.
