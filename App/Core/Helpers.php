<?php

function route(string $name, array $params = []){
    return \Reschit\App\Core\Route::url($name,$params);
}

?>