<?php


$uri = $_SERVER['REQUEST_URI'];

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    $appRoot = dirname(__DIR__);
    $path = preg_replace('#^App#', $appRoot, $path);

    if (file_exists($path)) {
        require_once $path;
        return true;
    }

    return false;
});

$routes = require_once '../config/routes.php';

if (isset($routes[$uri])) {
    list($class, $method) = $routes[$uri];
    //print_r($class);

    $obj = new $class();
    $result = $obj->$method();

    if (!empty($result)) {
        $viewName = $result['view'];
        $data = $result['data'];

        extract($data);

        require_once "../Views/$viewName.phtml";
    }

} else {
    require_once '../Views/404.html';
}

?>




