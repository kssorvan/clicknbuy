<?php// Http/controller/client/trade-in/index.php


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

view("client/trade-in/index.view.php");