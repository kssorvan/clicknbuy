<?php

return [
    'database' => [
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'port'     => getenv('DB_PORT') ?: 3306,
        'database' => getenv('DB_NAME') ?: 'clicknbuy',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'charset'  => 'utf8mb4',
    ],
    'cloudinary' => [
        'cloud_name' => 'unknownEAE46',
        'api_key'    => '393515422477893',
        'api_secret' => 'Cy-ROBqmcN01AOeEjD85-wAPa68',
    ],
];
