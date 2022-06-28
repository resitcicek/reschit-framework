<?php
use \Reschit\App\Core\Route;

/** Prefix */
Route::get('/',function (){
    return 'api home page';
});
Route::get('/users',function (){
    return 'api users page';
});