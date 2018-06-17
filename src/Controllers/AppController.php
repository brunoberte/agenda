<?php
declare(strict_types=1);

namespace Agenda\Controllers;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Zend\Diactoros\ServerRequestFactory;

abstract class AppController
{
    protected $server_request;

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_POST);
        }
        $this->server_request = ServerRequestFactory::fromGlobals();
    }

    protected function renderJson($content = [], $http_code = 200) {
        http_response_code($http_code);
        header('Content-Type: application/json');
        echo json_encode($content);
    }

    protected function render($template, $vars = []) {
        $loader = new Twig_Loader_Filesystem(SRC_DIR . 'Views');
        $twig = new Twig_Environment($loader, array(
//            'cache' => ROOT . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'twig_cache',
        ));

        $vars = array_merge($vars, [
            'current_uri' => $this->getCurrentUri()
        ]);

        echo $twig->render($template, $vars);
    }

    private function getCurrentUri() {
        $uri = $this->server_request->getServerParams()['REQUEST_URI'];
        $length = strpos($uri, '/', 1);
        if ($length === false) {
            return $uri;
        }
        return substr($uri, 0, $length);
    }
}


