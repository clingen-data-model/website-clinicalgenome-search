<?php

return [
    'ssl' => false,
    'connection' => 'default',
    'host'   => env('DB_HOST', 'localhost'),
    'port'   => env('DB_PORT', '7474'),
    'username' => env('DB_USERNAME', 'neo4j'),
    'password' => env('DB_PASSWORD', 'neo4j')
];