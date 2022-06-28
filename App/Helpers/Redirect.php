<?php

namespace Reschit\App\Helpers;

class Redirect
{
    public static function to(string $to, int $status = 301){

        header('Location:' . getenv('BASE_PATH') . $to, true, $status );
    }

}