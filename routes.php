<?php

/**** Client Interface Routes ****/

// Basic Pages
$router->get('/', 'client/index.php');

$router->get('/about', 'client/about.php');
$router->get('/contact', 'client/contact.php');

// Product Routes
$router->get('/products', 'client/products/index.php');
$router->get('/product/{id}', 'client/products/show.php');

// Cart Routes
$router->get('/cart', 'client/cart/index.php');
$router->get('/addcart/{id}', 'client/cart/addcart.php');
$router->post('/removecart', 'client/cart/remove.php');
$router->post('/updatecart', 'client/cart/update.php');
$router->post('/cart/checkout', 'client/cart/checkout.php');

// User Account Routes
$router->get('/profile', 'client/profile/index.php');
$router->post('/register', 'client/registration/index.php')->only('guest');
$router->post('/login', 'client/login/index.php')->only('guest');
$router->post('/logout', 'client/login/destroy.php')->only('auth');

// Motorcycle-specific Routes
$router->get('/motorcycles', 'client/motorcycles/index.php');
$router->get('/motorcycle/{id}', 'client/motorcycles/show.php');
$router->get('/test-ride/request/{id}', 'client/test-rides/request.php')->only('auth');
$router->post('/test-ride/submit', 'client/test-rides/store.php')->only('auth');
$router->get('/financing/calculator/{id}', 'client/financing/calculator.php');
$router->post('/financing/apply', 'client/financing/apply.php')->only('auth');
$router->post('/reviews/submit', 'client/reviews/store.php')->only('auth');
$router->get('/trade-in', 'client/trade-in/index.php')->only('auth');
$router->post('/trade-in/submit', 'client/trade-in/store.php')->only('auth');

/**** Admin Interface Routes ****/

// Dashboard Home
$router->get('/dashboard', 'dashboard/index.php')->only('superuser');

// Product Management
$router->get('/tbproducts', 'dashboard/products/index.php')->only('superuser');
$router->post('/tbproducts', 'dashboard/products/index.php')->only('superuser');
$router->post('/tbproducts/update', 'dashboard/products/index.php')->only('superuser');
$router->post('/tbproducts/delete', 'dashboard/products/index.php')->only('superuser');

// Category Management
$router->get('/tbcategories', 'dashboard/categories/index.php')->only('superuser');
$router->post('/tbcategories', 'dashboard/categories/index.php')->only('superuser');
$router->post('/tbcategories/update', 'dashboard/categories/index.php')->only('superuser');
$router->post('/tbcategories/delete', 'dashboard/categories/index.php')->only('superuser');

// Order Management
$router->get('/tborders', 'dashboard/orders/index.php')->only('superuser');
$router->post('/tborders', 'dashboard/orders/index.php')->only('superuser');
$router->post('/tborders/update', 'dashboard/orders/index.php')->only('superuser');
$router->post('/tborders/delete', 'dashboard/orders/index.php')->only('superuser');

// User Management
$router->get('/tbusers', 'dashboard/users/index.php')->only('superuser');
$router->post('/tbusers/update', 'dashboard/users/index.php');
$router->post('/tbusers/delete', 'dashboard/users/index.php')->only('superuser');

// Motorcycle Management
$router->get('/motorcycles/admin', 'dashboard/motorcycles/index.php')->only('superuser');
$router->get('/motorcycles/create', 'dashboard/motorcycles/create.php')->only('superuser');
$router->post('/motorcycles', 'dashboard/motorcycles/store.php')->only('superuser');
$router->get('/motorcycles/edit/{id}', 'dashboard/motorcycles/edit.php')->only('superuser');
$router->post('/motorcycles/update', 'dashboard/motorcycles/update.php')->only('superuser');
$router->post('/motorcycles/delete', 'dashboard/motorcycles/delete.php')->only('superuser');

// Brand Management
$router->get('/brands', 'dashboard/brands/index.php')->only('superuser');
$router->post('/brands', 'dashboard/brands/store.php')->only('superuser');
$router->post('/brands/update', 'dashboard/brands/update.php')->only('superuser');
$router->post('/brands/delete', 'dashboard/brands/delete.php')->only('superuser');

// Test Ride Management
$router->get('/test-rides/admin', 'dashboard/test-rides/index.php')->only('superuser');
$router->post('/test-rides/update-status', 'dashboard/test-rides/update-status.php')->only('superuser');

// Financing Options Management
$router->get('/financing-options', 'dashboard/financing/index.php')->only('superuser');
$router->post('/financing-options', 'dashboard/financing/store.php')->only('superuser');
$router->post('/financing-options/update', 'dashboard/financing/update.php')->only('superuser');
$router->post('/financing-options/delete', 'dashboard/financing/delete.php')->only('superuser');

// Payment routes
$router->get('/payment', 'client/payment/index.php')->only('auth');
$router->post('/payment/process', 'client/payment/process.php')->only('auth');
$router->get('/order/confirmation/{id}', 'client/order/confirmation.php')->only('auth');

// Webhook routes
$router->post('/webhooks/stripe', 'webhooks/stripe.php');

// Add these to your routes.php
$router->get('/payment/aba-payway', 'client/payment/aba-payway.php')->only('auth');
$router->get('/payment/callback', 'client/payment/callback.php');
$router->get('/order/confirmation/{id}', 'client/order/confirmation.php')->only('auth');

// Add these to your routes.php
$router->get('/payment/paypal', 'client/payment/paypal.php')->only('auth');
$router->get('/payment/paypal-success', 'client/payment/paypal-success.php')->only('auth');
$router->get('/payment/paypal-cancel', 'client/payment/paypal-cancel.php')->only('auth');

$router->get('/cart/checkout', 'client/cart/checkout.php')->only('auth');
// Add this to your routes.php
$router->post('/dashboard/orders/mark-paid', 'dashboard/orders/mark-paid.php')->only('superuser');
// Add this to your routes.php
$router->get('/payment/cod', 'client/payment/cod.php')->only('auth');