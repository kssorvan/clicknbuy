<?php

/**** Client Interface */

$router->get('/', 'client/index.php');
$router->get('/about', 'client/about.php');
$router->get('/contact', 'client/contact.php');
$router->get('/products', 'client/products/index.php');
$router->get('/product/{id}', 'client/products/show.php');
$router->get('/cart', 'client/cart/index.php');
$router->get('/addcart/{id}', 'client/cart/addcart.php');
$router->get('/profile', 'client/profile/index.php');
$router->post('/removecart', 'client/cart/remove.php');
$router->post('/updatecart', 'client/cart/update.php');
$router->post('/cart/checkout', 'client/cart/checkout.php');
// user regisration
$router->post('/register', 'client/registration/index.php')->only('guest');
$router->post('/login', 'client/login/index.php')->only('guest');
$router->post('/logout', 'client/login/destroy.php')->only('auth');
/**** Admin Interface */

$router->get('/dashboard', 'dashboard/index.php')->only('superuser');

//product dashboard
$router->get('/tbproducts', 'dashboard/products/index.php')->only('superuser');
$router->post('/tbproducts', 'dashboard/products/index.php')->only('superuser');
$router->post('/tbproducts/update', 'dashboard/products/index.php')->only('superuser');
$router->post('/tbproducts/delete', 'dashboard/products/index.php')->only('superuser');

//category dashboard
$router->get('/tbcategories', 'dashboard/categories/index.php')->only('superuser');
$router->post('/tbcategories', 'dashboard/categories/index.php')->only('superuser');
$router->post('/tbcategories/update', 'dashboard/categories/index.php')->only('superuser');
$router->post('/tbcategories/delete', 'dashboard/categories/index.php')->only('superuser');

// order dashboard
$router->get('/tborders', 'dashboard/orders/index.php')->only('superuser');
$router->post('/tborders', 'dashboard/orders/index.php')->only('superuser');
$router->post('/tborders/update', 'dashboard/orders/index.php')->only('superuser');
$router->post('/tborders/delete', 'dashboard/orders/index.php')->only('superuser');

// user dashboard

$router->get('/tbusers', 'dashboard/users/index.php')->only('superuser');
$router->post('/tbusers/update', 'dashboard/users/index.php');
$router->post('/tbusers/delete', 'dashboard/users/index.php')->only('superuser');
