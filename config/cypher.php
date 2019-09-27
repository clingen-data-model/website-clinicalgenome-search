<?php

return [
    'ssl' => false,
    'connection' => 'bolt',
    'host'   => env('NEO4J_HOST', 'localhost'),
    'port'   => env('NEO4J_PORT', '7474'),
    'username' => env('NEO4J_USERNAME', 'neo4j'),
    'password' => env('NEO4J_PASSWORD', 'neo4j')
];