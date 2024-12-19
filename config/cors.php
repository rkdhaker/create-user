<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Add all API routes here

    'allowed_methods' => ['*'], // You can specify methods like ['GET', 'POST'] or allow all

    'allowed_origins' => ['*'], // The origin that can make requests

    'allowed_headers' => ['*'], // Allow any headers or specify them like ['Content-Type', 'Authorization']

    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];