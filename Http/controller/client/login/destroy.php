
<?php
// Http/controller/client/login/destroy.php
use Core\Authenticator;

$authenticator = new Authenticator();
$authenticator->logout();

redirect('/');