<?php
use \Reschit\App\Core\Route;

/** Get class's function */
Route::get('/',function(){
   return view('index');
});

/** Get method with parameters */
Route::get('/user/:id1/:id2','User@detail')->name('user');

/** Where method  */
Route::get('/@:username', function ($username){
    return 'üye adınız: '. $username;
})->where('username','[a-z]+');

/** Search method */
Route::get('/search/:search', function ($search){
    return 'aranan kelime: '. rawurldecode($search);
})->where('search','.*');

/** Prefix */
Route::prefix('/admin')->group(function (){
    Route::get('/',function (){
        return 'admin home page';
    });
    Route::get('/users',function (){
        return 'admin users page';
    });
});

/** Redirect method */
Route::redirect('/ankara', '/hatay', 301);