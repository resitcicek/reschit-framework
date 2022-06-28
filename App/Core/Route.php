<?php

namespace Reschit\App\Core;
use Reschit\App\Helpers\Redirect;

class Route
{

    public static $patterns = [
        ':id[0-9]?' => '([0-9]+)',
        ':url[0-9]?' => '([0-9a-zA-z-_]+)'
    ];
    public static array $routes = [];
    public static bool $isRoute = false;
    public static string $prefix = '';


    /**
     * @param $path
     * @param $callback
     * @return Route
     */
    public static function get($path, $callback): Route
    {
        self::$routes['get'][self::$prefix . $path] = [
            'callback' => $callback
        ];
        return new self();
    }

    /**
     * @param $path
     * @param $callback
     * @return void
     */
    public static function post($path, $callback): void
    {
        self::$routes['post'][$path] = [
            'callback' => $callback
        ];
    }

    public static function dispatch()
    {

        $url = self::getURL();
        $method = self::getMethod();
        foreach (self::$routes[$method] as $path => $props) {

            foreach(self::$patterns as $key => $pattern){
                $path = preg_replace('#'. $key . '#',$pattern,$path);
            }

            $pattern = '#^' . $path . '$#';

            if (preg_match($pattern, $url, $params)) {
                self::$isRoute = true;
                array_shift($params);
                (count($params) > 0) ? $params = $params[0] : null;
                if(isset($props['redirect'])){
                    Redirect::to($props['redirect'], $props['status']);
                }
                else {
                    $callback = $props['callback'];

                    if (is_callable($callback)) {
                        echo call_user_func($callback, $params);
                    } elseif (is_string($callback)) {
                        [$controllerName, $methodName] = explode('@', $callback);
                        $controllerName = 'Reschit\App\Controllers\\' . $controllerName;
                        $controller = new $controllerName();
                        echo call_user_func([$controller, $methodName], $params);
                    }
                }
            }
        }
        self::isRoute();

    }

    public static function isRoute(): void
    {
        if (self::$isRoute == false) {
            die('404 not found error.');
        }
    }


    public static function getURL(): string
    {
        return str_replace(getenv('BASE_PATH'), null, $_SERVER['REQUEST_URI']);
    }


    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


    public function name($name): void{
        $key = array_key_last(self::$routes['get']);
        self::$routes['get'][$key]['name'] = $name;

    }

    /**
     * @param string $name
     * @param array $params
     * @return array|int|string|string[]|null
     */
    public static function url(string $name, array $params = []){
        $route = array_key_first(array_filter(self::$routes['get'], function($route) use($name){
            return $route['name'] === $name;
        }));
        return str_replace(array_map(fn($key)=>':' . $key), array_values($params), $route);
    }

    /**
     * @param $prefix
     * @return void
     */
    public static function prefix($prefix): Route{
        self::$prefix = $prefix;
        return new self;
    }

    /**
     * @param \Closure $closure
     * @return void
     */
    public static function group(\Closure $closure): void {
        $closure();
        self::$prefix = '';
    }

    public static function where($key, $pattern){
        self::$patterns[':'.$key] = '(' . $pattern . ')';

    }

    public static function redirect($from, $to, $status = 301){
        self::$routes['get'][$from] = [
            'redirect' => $to,
            'status' => $status
        ];
    }
}