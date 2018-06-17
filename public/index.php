<?php
declare(strict_types=1);

use FastRoute\RouteCollector;

define('ROOT', dirname(dirname(__FILE__)));
define('SRC_DIR', ROOT . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/env.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'Agenda\\Controllers\\DashboardController::index');
    $r->addRoute('GET', '/contacts', 'Agenda\\Controllers\\ContactsController::index');

    $r->addGroup('/api/contact', function (RouteCollector $r) {
        $r->addRoute('GET', '', 'Agenda\\Controllers\\ApiContactsController::index');
        $r->addRoute('GET', '/{id:\d+}', 'Agenda\\Controllers\\ApiContactsController::view');
        $r->addRoute('POST', '/', 'Agenda\\Controllers\\ApiContactsController::create');
        $r->addRoute('PUT', '/{id:\d+}', 'Agenda\\Controllers\\ApiContactsController::update');
        $r->addRoute('DELETE', '/{id:\d+}', 'Agenda\\Controllers\\ApiContactsController::delete');
    });
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($class, $method) = explode('::', $handler, 2);
        call_user_func_array(array(new $class, $method), $vars);
        break;
}
