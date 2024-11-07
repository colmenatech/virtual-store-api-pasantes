<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configura los permisos de CORS para definir qué operaciones pueden
    | ejecutarse desde otros dominios. Aquí puedes ajustar los valores según
    | tus necesidades para el desarrollo en localhost.
    |
    */

    'paths' => [
        'api/*',           // Permite todas las rutas de la API
        'sanctum/csrf-cookie', // Permite las cookies de CSRF de Sanctum para autenticación
    ],

    'allowed_methods' => [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH'  // Permite solo los métodos específicos
    ],

    'allowed_origins' => [
        'http://localhost' // Permite solo solicitudes desde localhost
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type', 'X-Requested-With', 'Authorization', 'X-CSRF-Token'
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Habilita el uso de cookies de sesión para autenticación 

];
