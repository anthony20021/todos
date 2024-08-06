<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'database'),

    'lifetime' => env('SESSION_LIFETIME', 120),

    'expire_on_close' => false,

    'encrypt' => false,

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => 'sessions',

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    'path' => '/',

    'domain' => env('SESSION_DOMAIN', null),

    // Assurez-vous que ce paramètre est activé pour les cookies multi-origine.
    'secure' => env('SESSION_SECURE_COOKIE', true), 

    'http_only' => true,

    // Assurez-vous que cette option est réglée sur 'none' pour accepter les requêtes multi-origines.
    'same_site' => 'none',

    'partitioned' => false,

];
