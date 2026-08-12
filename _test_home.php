<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create("/", "GET");
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
$content = $response->getContent();
if (str_contains($content, "laravel") && str_contains($content, "Whoops")) {
    echo "ERROR: Laravel error page\n";
    $log = file_get_contents(__DIR__ . "/storage/logs/laravel.log");
    $lines = explode("\n", trim($log));
    $lastError = array_slice($lines, -30);
    echo implode("\n", $lastError) . "\n";
} else {
    echo "SUCCESS: " . strlen($content) . " chars\n";
    $checks = ["Kenya on Wheels", "Shishi Footsteps", "Explore Itinerary", "From USD 4,500"];
    foreach ($checks as $check) {
        echo str_contains($content, $check) ? "  FOUND: $check\n" : "  MISSING: $check\n";
    }
}
