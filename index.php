<?php
require __DIR__ . '/vendor/autoload.php';

use \Reschit\App\Core\{App,Route};

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

require __DIR__ . '/App/Routes/Web.php';
Route::prefix('/api')->group(function (){
    require __DIR__.'/App/Routes/Api.php';
});

Route::dispatch();
?>