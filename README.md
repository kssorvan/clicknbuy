🛒 ClicknBuy
ClicknBuy is a custom-built e-commerce web application developed with PHP, designed for seamless online shopping of products and motorcycles.
It features user authentication, shopping cart, test ride requests, and a full admin dashboard for managing products, orders, and users.
Built on a custom MVC-like framework with a file-based router, session-based authentication, and a MySQL database.

📑 Table of Contents
Features

Technologies

Project Structure

Installation

Configuration

Usage

Routes

Contributing

License

✨ Features
User Authentication: Register, login, logout, and profile management with role-based access (User, Admin, Superuser).

Shopping Cart: Add, update, remove items, and checkout; guest carts merge on login.

Product Management: Browse and manage products and motorcycles (Admin CRUD).

Test Ride Requests: Request and manage motorcycle test rides.

Admin Dashboard: View metrics, export earnings, manage users, products, and orders.

Role-Based Middleware: Restrict routes by user roles.

Session-Based Cart: Guest users can add to cart without an account.

⚙️ Technologies
PHP 7.4+ – Backend logic

MySQL – Database

Composer – Dependency management (e.g., vlucas/phpdotenv)

Bootstrap – Frontend styling

SB Admin 2 – Admin dashboard template

JavaScript – Client-side interactivity

🗂 Project Structure
bash
Copy
Edit
clicknbuy/
├── Core/
│   ├── App.php          # Application bootstrap
│   ├── Authenticator.php # Authentication logic
│   ├── Database.php     # Database connection
│   ├── Router.php       # Routing system
│   ├── Session.php      # Session management
│   ├── Validator.php    # Input validation
├── controllers/
│   ├── client/          # Client-side controllers
│   ├── dashboard/       # Admin dashboard controllers
├── public/
│   ├── asset/           # Frontend assets (includes SB Admin 2)
│   ├── index.php        # Entry point
├── views/
│   ├── client/          # Client-side views
│   ├── dashboard/       # Admin views
│   ├── partials/        # Reusable view components
├── helpers.php          # Helper functions
├── routes.php           # Route definitions
├── bootstrap.php        # Application setup
├── .env                 # Environment variables
├── composer.json        # Composer configuration
├── logs/                # Error logs
📥 Installation
Prerequisites
PHP >= 7.4

MySQL

Composer

Web server (e.g., Apache via Laragon)

Node.js (optional, for asset compilation)

Steps
Clone the repository:

bash
Copy
Edit
git clone <repository-url> clicknbuy
cd clicknbuy
Install dependencies:

bash
Copy
Edit
composer install
Setup environment file:

bash
Copy
Edit
cp .env.example .env
Edit .env with your database credentials:

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
Create the database:

Create a MySQL database named clicknbuy.

Import SQL scripts if provided.

Configure web server:

Point server to the public/ directory.

Enable mod_rewrite for clean URLs.

Start application:

Access via http://localhost/clicknbuy.

⚙️ Configuration
Database: Managed via .env or Core/Database.php.

Sessions: Configured in public/index.php.

Logging: Errors stored in logs/error.log.

Static Assets: Ensure public assets (CSS, JS) are properly linked.

🚀 Usage
Access
Visit: http://localhost/clicknbuy

Default Accounts

Role	Email	Password
Admin	admin@example.com	admin123
Superuser	superuser@example.com	super123
User	user@example.com	user123
User Features
Browse products and motorcycles.

Add items to cart and checkout.

Request test rides.

Admin Features
Dashboard with metrics.

Manage products, categories, brands, motorcycles, orders, and test rides.

Export earnings reports.

Superuser Features
Manage users (update/delete).

🛣 Routes
Public Routes
GET / — Home

GET /products — View products

GET /product/{id} — Product details

GET /motorcycles — View motorcycles

GET /motorcycle/{id} — Motorcycle details

Guest Routes
GET|POST /login

GET|POST /registration

Authenticated Routes
GET /profile

GET /cart

POST /addcart/{id}

POST /cart/checkout

GET /test-ride/request/{id}

Admin Routes
/dashboard

/tbproducts

/tbcategories

/tborders

/motorcycles/admin

/brands

/test-rides/admin

Superuser Routes
/tbusers/update

/tbusers/delete

📜 License
This project is licensed under the MIT License.
See the LICENSE file for more details.

