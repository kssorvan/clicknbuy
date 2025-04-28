ClicknBuy
ClicknBuy is a custom-built e-commerce web application developed in PHP, designed to facilitate online shopping for products and motorcycles. It features user authentication, a shopping cart, test ride requests, and an admin dashboard for managing products, orders, and users. The application uses a custom MVC-like framework with a file-based router, session-based authentication, and a MySQL database.
Table of Contents
Features (#features)

Technologies (#technologies)

Project Structure (#project-structure)

Installation (#installation)

Configuration (#configuration)

Usage (#usage)

Routes (#routes)

Contributing (#contributing)

License (#license)

Features
User Authentication: Register, login, logout, and profile management with role-based access (user, admin, superuser).

Shopping Cart: Add, update, remove items, and checkout with cart merging for guest users upon login.

Product Management: Browse products and motorcycles, with admin CRUD operations.

Test Ride Requests: Users can request test rides for motorcycles, with admin approval/rejection.

Admin Dashboard: View metrics (orders, test rides), export earnings, and manage users, products, and orders.

Role-Based Middleware: Restrict routes to authenticated users, admins, or superusers.

Session-Based Cart: Guest users can add items to a session-based cart, merged to the database on login.

Technologies
PHP: 7.4+ for backend logic.

MySQL: Database for storing users, products, carts, orders, and test rides.

Composer: Dependency management (e.g., vlucas/phpdotenv).

Bootstrap: Frontend styling.

SB Admin 2: Admin dashboard template.

JavaScript: Client-side interactivity.

Project Structure

clicknbuy/
├── Core/
│   ├── App.php              # Application bootstrap
│   ├── Authenticator.php    # Handles authentication logic
│   ├── Database.php         # Database connection and queries
│   ├── Router.php           # File-based routing
│   ├── Session.php          # Session management
│   ├── Validator.php        # Input validation
├── controllers/
│   ├── client/              # Client-side controllers
│   │   ├── cart/
│   │   │   ├── index.php
│   │   │   ├── checkout.php
│   │   ├── login/
│   │   │   ├── index.php
│   │   ├── registration/
│   │   │   ├── index.php
│   ├── dashboard/           # Admin dashboard controllers
│   │   ├── index.php
├── public/
│   ├── asset/
│   │   ├── sb-admin-2/     # Admin template assets
│   ├── index.php            # Entry point
├── views/
│   ├── client/              # Client views
│   │   ├── login/
│   │   │   ├── index.php
│   │   ├── partials/
│   │   │   ├── head.php
│   │   │   ├── nav.php
│   │   │   ├── footer.php
│   ├── dashboard/           # Admin views
│   ├── partials/            # Shared views
├── helpers.php              # Helper functions
├── routes.php               # Route definitions
├── bootstrap.php            # Bootstrap logic
├── .env                     # Environment variables
├── composer.json            # Composer dependencies
├── logs/                    # Error logs

Installation
Prerequisites
PHP >= 7.4

MySQL

Composer

Web server (e.g., Apache via Laragon)

Node.js (optional, for asset compilation)

Steps
Clone the Repository:
bash

git clone <repository-url> clicknbuy
cd clicknbuy

Install Dependencies:
bash

composer install

Set Up Environment:
Copy .env.example to .env:
bash

cp .env.example .env

Edit .env with your database credentials:
env

APP_ENV=development
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clicknbuy
DB_USERNAME=root
DB_PASSWORD=

Create Database:
Create a MySQL database named clicknbuy.

Run SQL scripts (if provided) to create tables (users, products, cart, orders, test_rides, categories, brands).

Configure Web Server:
Point your web server (e.g., Laragon) to the public/ directory.

Ensure mod_rewrite is enabled for clean URLs.

Start the Server:
Using Laragon, start Apache and MySQL.

Access the application at http://localhost/clicknbuy.

Configuration
Database: Update Core\Database.php with your database connection details if not using .env.

Sessions: Session settings are configured in public/index.php (HTTP-only, secure cookies).

Logging: Errors are logged to logs/error.log in production mode.

Static Assets: Ensure public/asset/sb-admin-2/ contains CSS/JS for the admin dashboard.

Usage
Access the Application:
Visit http://localhost/clicknbuy.

Register a new account or use seeded credentials:
Admin: admin@example.com / admin123

Superuser: superuser@example.com / super123

User: user@example.com / user123

User Features:
Browse products (/products) and motorcycles (/motorcycles).

Add items to cart (/addcart/{id}).

Request test rides (/test-ride/request/{id}).

View profile (/profile) and cart (/cart).

Checkout (/cart/checkout).

Admin Features:
Access the dashboard (/dashboard) as an admin or superuser.

Manage products (/tbproducts), categories (/tbcategories), orders (/tborders), motorcycles (/motorcycles/admin), brands (/brands), and test rides (/test-rides/admin).

Export earnings (/dashboard/export-earnings).

Superusers can update/delete users (/tbusers/update, /tbusers/delete).

Guest Features:
Guests can browse products and add items to a session-based cart, which merges into the database upon login.

Routes
Defined in routes.php, routes are grouped by middleware (guest, auth, admin, strict_superuser). Key routes include:
Public Routes
GET /: Home page

GET /products: List products

GET /product/{id}: View product

GET /motorcycles: List motorcycles

GET /motorcycle/{id}: View motorcycle

Guest Routes
GET|POST /login: Login page and submission

GET|POST /registration: Registration page and submission

Authenticated Routes
GET /profile: User profile

GET /cart: View cart

POST /addcart/{id}: Add to cart

POST /updatecart: Update cart quantities

POST /removecart: Remove cart item

POST /cart/checkout: Checkout

GET /test-ride/request/{id}: Request test ride

POST /test-ride/submit: Submit test ride request

GET|POST /logout: Logout

Admin Routes
GET /dashboard: Admin dashboard

GET /dashboard/export-earnings: Export earnings as CSV

GET /dashboard/pending-test-rides: View pending test rides

GET|POST /tbproducts: Manage products

GET|POST /tbcategories: Manage categories

GET|POST /tborders: Manage orders

GET|POST /motorcycles/admin: Manage motorcycles

GET|POST /brands: Manage brands

GET|POST /test-rides/admin: Manage test rides

Superuser Routes
POST /tbusers/update: Update user details

DELETE /tbusers/delete: Delete user

Contributing
To contribute:
Fork the repository.

Create a feature branch (git checkout -b feature/YourFeature).

Commit changes (git commit -m 'Add YourFeature').

Push to the branch (git push origin feature/YourFeature).

Open a pull request.

License
This project is licensed under the MIT License. See the LICENSE file for details.

