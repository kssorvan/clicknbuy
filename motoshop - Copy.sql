-- Drop existing tables if they exist (for a clean setup)
DROP TABLE IF EXISTS customer_communications;
DROP TABLE IF EXISTS payment_transactions;
DROP TABLE IF EXISTS product_documents;
DROP TABLE IF EXISTS trade_in_requests;
DROP TABLE IF EXISTS product_reviews;
DROP TABLE IF EXISTS motorcycle_features;
DROP TABLE IF EXISTS product_financing;
DROP TABLE IF EXISTS financing_options;
DROP TABLE IF EXISTS test_rides;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS motorcycle_specs;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS motorcycle_brands;
DROP TABLE IF EXISTS categories;

-- Categories table
CREATE TABLE categories (
    category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description VARCHAR(1000),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (category_name)
);

-- Motorcycle Brands table
CREATE TABLE motorcycle_brands (
    brand_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(100) NOT NULL,
    logo_url VARCHAR(255),
    description VARCHAR(1000),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (brand_name)
);

-- Products table
CREATE TABLE products (
    product_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(1000),
    price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2),
    stock INT UNSIGNED NOT NULL DEFAULT 0 CHECK (stock >= 0),
    category_id INT UNSIGNED,
    image_url VARCHAR(255),
    public_id VARCHAR(255),
    featured BOOLEAN DEFAULT FALSE,
    promotion_type ENUM('featured', 'big_sale', 'weekly_deal', 'new_arrival', 'none') DEFAULT 'none',
    promotion_expiry TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Users table
CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'superuser') DEFAULT 'user',
    phone VARCHAR(20),
    address VARCHAR(1000),
    profile_image_url VARCHAR(255),
    last_login TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_email CHECK (email LIKE '%@%.%')
);

-- Motorcycle Specifications table
CREATE TABLE motorcycle_specs (
    spec_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    brand_id INT UNSIGNED,
    model_year SMALLINT UNSIGNED NOT NULL, -- Removed CHECK constraint
    engine_type VARCHAR(100),
    engine_displacement SMALLINT UNSIGNED,
    horsepower SMALLINT UNSIGNED,
    torque VARCHAR(50),
    transmission_type VARCHAR(50),
    gear_count TINYINT UNSIGNED,
    fuel_capacity DECIMAL(5, 2),
    fuel_economy VARCHAR(50),
    seat_height SMALLINT UNSIGNED,
    weight DECIMAL(6, 2),
    vin VARCHAR(17),
    mileage INT UNSIGNED,
    `condition` ENUM('New', 'Used', 'Certified Pre-Owned') DEFAULT 'New',
    color VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES motorcycle_brands(brand_id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Additional Images table
CREATE TABLE product_images (
    image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    image_order TINYINT UNSIGNED DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Cart table
CREATE TABLE cart (
    cart_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1 CHECK (quantity > 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY (user_id, product_id)
);

-- Orders table
CREATE TABLE orders (
    order_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL CHECK (total_amount >= 0),
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'canceled') DEFAULT 'pending',
    shipping_address VARCHAR(1000) NOT NULL,
    payment_method ENUM('Credit Card', 'Cash on Delivery', 'Bank Transfer') DEFAULT 'Cash on Delivery',
    shipping_method VARCHAR(50),
    tax_amount DECIMAL(10, 2) DEFAULT 0 CHECK (tax_amount >= 0),
    shipping_amount DECIMAL(10, 2) DEFAULT 0 CHECK (shipping_amount >= 0),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    card_last_four CHAR(4),
    cod_instructions VARCHAR(1000),
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Order Items table
CREATE TABLE order_items (
    item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL CHECK (quantity > 0),
    price DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Test Rides table
CREATE TABLE test_rides (
    ride_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    requested_date DATE NOT NULL,
    requested_time TIME NOT NULL,
    status ENUM('pending', 'approved', 'completed', 'canceled') DEFAULT 'pending',
    notes VARCHAR(1000),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Financing Options table
CREATE TABLE financing_options (
    option_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(1000),
    interest_rate DECIMAL(5, 2) NOT NULL CHECK (interest_rate >= 0),
    min_term TINYINT UNSIGNED NOT NULL,
    max_term TINYINT UNSIGNED NOT NULL,
    min_downpayment_percentage DECIMAL(5, 2) CHECK (min_downpayment_percentage >= 0 AND min_downpayment_percentage <= 100),
    requirements VARCHAR(1000),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product Financing table
CREATE TABLE product_financing (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    option_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (option_id) REFERENCES financing_options(option_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY (product_id, option_id)
);

-- Motorcycle Features table
CREATE TABLE motorcycle_features (
    feature_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    feature_name VARCHAR(100) NOT NULL,
    feature_description VARCHAR(1000),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Reviews table
CREATE TABLE product_reviews (
    review_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(255),
    comment TEXT,
    verified_purchase BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY (product_id, user_id)
);

-- Trade-In Requests table
CREATE TABLE trade_in_requests (
    request_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year SMALLINT UNSIGNED NOT NULL, -- Removed CHECK constraint
    mileage INT UNSIGNED NOT NULL,
    `condition` ENUM('Excellent', 'Good', 'Fair', 'Poor') NOT NULL,
    description VARCHAR(1000),
    images TEXT,
    estimated_value DECIMAL(10, 2) CHECK (estimated_value >= 0),
    status ENUM('pending', 'evaluated', 'accepted', 'rejected') DEFAULT 'pending',
    notes VARCHAR(1000),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Documents table
CREATE TABLE product_documents (
    document_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    document_type ENUM('Title', 'Manual', 'Service History', 'Warranty', 'Other') NOT NULL,
    document_url VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Payment Transactions table
CREATE TABLE payment_transactions (
    transaction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    payment_provider VARCHAR(50) NOT NULL,
    provider_transaction_id VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL CHECK (amount >= 0),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL,
    payment_details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Customer Communications table
CREATE TABLE customer_communications (
    communication_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED,
    user_id INT UNSIGNED,
    communication_type ENUM('email', 'sms', 'phone') NOT NULL,
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Indexes for performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_promotion_type ON products(promotion_type);
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_featured ON products(featured);
CREATE INDEX idx_products_is_deleted ON products(is_deleted);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_is_deleted ON orders(is_deleted);
CREATE INDEX isoftware engineerx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_product_reviews_product ON product_reviews(product_id);
CREATE INDEX idx_cart_user_id ON cart(user_id);
CREATE INDEX idx_test_rides_product_id ON test_rides(product_id);
CREATE INDEX idx_payment_transactions_order_id ON payment_transactions(order_id);

-- Full-text index for search (MySQL 5.6+)
ALTER TABLE products ADD FULLTEXT(name, description);

-- Insert sample data
INSERT INTO categories (category_name, description) VALUES
('Sport Bikes', 'High-performance motorcycles designed for speed and acceleration'),
('Cruisers', 'Comfortable motorcycles designed for long-distance riding'),
('Touring', 'Motorcycles built for long-distance travel with storage and comfort features'),
('Adventure', 'Versatile motorcycles for on and off-road riding'),
('Dirt Bikes', 'Off-road motorcycles designed for rough terrain'),
('Scooters', 'Step-through frame motorcycles with a platform for the feet'),
('Electric', 'Battery-powered motorcycles with electric motors'),
('Vintage', 'Classic and retro motorcycles'),
('Accessories', 'Non-motorcycle accessories like watches, wallets, and sunglasses');

INSERT INTO motorcycle_brands (brand_name, description) VALUES
('Honda', 'Japanese motorcycle manufacturer known for reliability'),
('Yamaha', 'Japanese motorcycle company with a focus on innovation'),
('Harley-Davidson', 'American motorcycle manufacturer famous for cruisers'),
('Kawasaki', 'Japanese manufacturer known for sport bikes'),
('BMW', 'German motorcycle maker focused on premium touring bikes'),
('Ducati', 'Italian motorcycle manufacturer known for racing and sport bikes'),
('Triumph', 'British motorcycle manufacturer with classic styling'),
('KTM', 'Austrian manufacturer specializing in off-road motorcycles');

INSERT INTO financing_options (name, description, interest_rate, min_term, max_term, min_downpayment_percentage) VALUES
('Standard Financing', 'Basic financing option for all customers', 5.99, 12, 60, 10.00),
('Premium Financing', 'Low-interest option for qualified buyers', 3.99, 24, 72, 15.00),
('Zero Down Special', 'Special promotion with no down payment required', 7.99, 36, 60, 0.00);

INSERT INTO users (name, email, password, role) VALUES 
('John Doe', 'john@example.com', '$2y$10$someHashedPassword', 'user'),
('Jane Smith', 'jane@example.com', '$2y$10$anotherHashedPassword', 'user'),
('Admin User', 'admin@example.com', '$2y$10$adminHashedPassword', 'admin');

INSERT INTO products (name, price, original_price, image_url, category_id, description, stock, featured, promotion_type, promotion_expiry)
VALUES
('Stylish Watch', 99.99, NULL, 'asset/images/watch.jpg', 9, 'A sleek and modern wristwatch.', 50, TRUE, 'featured', NULL),
('Leather Wallet', 49.99, NULL, 'asset/images/wallet.jpg', 9, 'A durable leather wallet.', 30, TRUE, 'featured', NULL),
('Sunglasses', 29.99, NULL, 'asset/images/sunglasses.jpg', 9, 'Trendy sunglasses for all occasions.', 20, TRUE, 'featured', NULL),
('Honda CBR1000RR', 15999.99, NULL, 'asset/images/honda-cbr1000rr.jpg', 1, 'A high-performance sport bike with advanced technology.', 5, TRUE, 'featured', NULL),
('Yamaha MT-09', 9499.99, 10499.99, 'asset/images/yamaha-mt09.jpg', 1, 'A powerful sport bike with a 3-cylinder engine.', 8, FALSE, 'big_sale', NULL),
('Harley-Davidson Fat Boy', 19999.99, NULL, 'asset/images/harley-fatboy.jpg', 2, 'A classic cruiser with a bold design.', 3, FALSE, 'weekly_deal', '2025-04-04 23:59:59'),
('Kawasaki Ninja 400', 4999.99, NULL, 'asset/images/kawasaki-ninja400.jpg', 1, 'An entry-level sport bike for beginners.', 10, FALSE, 'new_arrival', NULL);

INSERT INTO motorcycle_specs (product_id, brand_id, model_year, engine_type, engine_displacement, horsepower, torque, transmission_type, gear_count, fuel_capacity, fuel_economy, seat_height, weight, vin, mileage, `condition`, color)
VALUES
(4, 1, 2023, 'Inline 4-cylinder', 1000, 189, '112 Nm', 'Manual', 6, 16.2, '40 MPG', 832, 201.4, 'JH2SC771XPK123456', 0, 'New', 'Red');

INSERT INTO orders 
(user_id, total_amount, status, shipping_address, payment_method, payment_status, card_last_four, cod_instructions) 
VALUES 
(1, 1999.99, 'processing', '123 Main St, Anytown, USA', 'Credit Card', 'paid', '4567', NULL),
(2, 2499.50, 'pending', '456 Elm Street, Somewhere City, USA', 'Cash on Delivery', 'pending', NULL, 'Please have exact cash amount ready. Delivery partner will collect payment.'),
(3, 3299.75, 'processing', '789 Oak Road, Another Town, USA', 'Bank Transfer', 'paid', NULL, NULL);
SELECT * FROM trade_in_requests;
-- Add the is_deleted column to the categories table
ALTER TABLE categories
ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE AFTER updated_at;

-- Add an index on is_deleted for performance
CREATE INDEX idx_categories_is_deleted ON categories(is_deleted);


-- Check the table structure
DESCRIBE categories;

-- Verify the index
SHOW INDEX FROM categories;
ALTER TABLE products ADD FULLTEXT(name, description);



CREATE TABLE cart (
    cart_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1 CHECK (quantity > 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY (user_id, product_id)
);
SELECT * FROM cart WHERE user_id = 1;
UPDATE products SET stock = 0 WHERE product_id = 4;
ALTER TABLE products ADD COLUMN reserved_stock INT UNSIGNED NOT NULL DEFAULT 0;
SELECT * FROM cart WHERE user_id = 1;


INSERT INTO products (name, price, original_price, image_url, category_id, description, stock, featured, promotion_type, promotion_expiry)
VALUES
('Stylish Watch', 99.99, NULL, 'asset/images/watch.jpg', 9, 'A sleek and modern wristwatch.', 50, TRUE, 'featured', NULL),
('Leather Wallet', 49.99, NULL, 'asset/images/wallet.jpg', 9, 'A durable leather wallet.', 30, TRUE, 'featured', NULL),
('Sunglasses', 29.99, NULL, 'asset/images/sunglasses.jpg', 9, 'Trendy sunglasses for all occasions.', 20, TRUE, 'featured', NULL),
('Honda CBR1000RR', 15999.99, NULL, 'asset/images/honda-cbr1000rr.jpg', 1, 'A high-performance sport bike with advanced technology.', 5, TRUE, 'featured', NULL),
('Yamaha MT-09', 9499.99, 10499.99, 'asset/images/yamaha-mt09.jpg', 1, 'A powerful sport bike with a 3-cylinder engine.', 8, FALSE, 'big_sale', NULL),
('Harley-Davidson Fat Boy', 19999.99, NULL, 'asset/images/harley-fatboy.jpg', 2, 'A classic cruiser with a bold design.', 3, FALSE, 'weekly_deal', '2025-04-04 23:59:59'),
('Kawasaki Ninja 400', 4999.99, NULL, 'asset/images/kawasaki-ninja400.jpg', 1, 'An entry-level sport bike for beginners.', 10, FALSE, 'new_arrival', NULL);