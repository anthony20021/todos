<?php
// Inclure le fichier autoload.php de Laravel pour initialiser l'application
require __DIR__.'/vendor/autoload.php';

// Initialiser l'application Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Obtenir l'instance de l'application Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Créer une requête HTTP pour exécuter `phpinfo()`
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Afficher les informations PHP avec `phpinfo()`
phpinfo();