<?php

function route(string $name, array $params = []){
    return \Reschit\App\Core\Route::url($name,$params);
}

function view($name, $data = []){
    return \Reschit\App\Core\View::run($name, $data);
}

?>