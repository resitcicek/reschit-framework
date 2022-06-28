<?php

namespace Reschit\App\Core;

class View
{
    public static function run(string $name, array $data = []){
        ob_start();
        require getenv('DIR') . '/Public/Views/' .  $name . '.php';
        return ob_get_clean();
    }

}