<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$acts = App\Models\Activity::with("translations","category","prices","seasons")->get();
foreach ($acts as $a) {
    $t = $a->translation();
    echo "=== #{$a->id} {$a->name} ===\n";
    echo "slug: {$a->slug}\n";
    echo "country: {$a->country}\n";
    echo "region: {$a->region}\n";
    echo "description: " . substr($a->description ?? "EMPTY", 0, 200) . "\n";
    echo "image: {$a->image}\n";
    echo "images: " . json_encode($a->images) . "\n";
    echo "trans_title: " . ($t->title ?? "N/A") . "\n";
    echo "trans_description: " . substr($t->description ?? "N/A", 0, 200) . "\n";
    echo "trans_location: " . ($t->location ?? "N/A") . "\n";
    echo "category_id: {$a->activity_category_id}\n";
    echo "category_name: " . ($a->category->name ?? "N/A") . "\n";
    echo "duration_hours: {$a->duration_hours}\n";
    echo "min_pax: {$a->min_pax}\n";
    echo "min_age: {$a->min_age}\n";
    echo "location: {$a->location}\n";
    echo "price: {$a->price}\n";
    echo "currency: {$a->currency}\n\n";
}
