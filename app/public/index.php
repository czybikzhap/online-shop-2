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

$services = require_once '../config/services.php';

$container = require_once '../Container.php';

$container = new Container($services);

if (isset($routes[$uri])) {
    list($class, $method) = $routes[$uri];

    $obj = $container->get($class);

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




