<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

app()->setLocale('ar');
$configs = App\Models\Configuration::where('key', 'city')->withTranslation()->get();
foreach ($configs as $c) {
    echo $c->id . ': [' . $c->name . '] (translation locale: ' . ($c->translation ? $c->translation->locale : 'null') . ")\n";
}

echo "\n--- Test without withTranslation ---\n";
$configs2 = App\Models\Configuration::where('key', 'city')->get();
foreach ($configs2 as $c) {
    echo $c->id . ': [' . $c->name . "]\n";
}
