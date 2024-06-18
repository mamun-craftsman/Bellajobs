<?php
require '../helper.php';
require basePath('vendor/autoload.php');
use Framework\Router;
use Framework\Session;
Session::start();
$router = new Router();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $uri = $uri['path']; //it has query also

$routs = require basePath('routes.php');
$router->route($uri);

?>