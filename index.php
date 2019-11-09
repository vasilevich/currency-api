<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/controller/Router.php";
$router = new Router();
$router->initRoutes();
