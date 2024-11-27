return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Ajusta según tus rutas
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://127.0.0.1:8000'], // Permite tu origen
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
