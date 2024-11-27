return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Ajusta según tus rutas
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Permite tu origen
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
