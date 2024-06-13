<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
$routes->add('/', 'Common\Controllers\Dashboard::index', ['namespace' => 'Admin','filter' => 'login']);
$routes->get('api','Api::index');
$routes->get("cron/clmpunch", "CronController::clmpunch");

