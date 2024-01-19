<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('location', 'Location\Controllers\Location::index');
    $routes->post('location/search','Location\Controllers\Location::search');
    $routes->match(['get','post'],'location/add', 'Location\Controllers\Location::add');
    $routes->match(['get','post'],'location/edit/(:segment)', 'Location\Controllers\Location::edit/$1');
    $routes->get('location/delete/(:segment)',   'Location\Controllers\Location::delete/$1');
    $routes->post('location/delete','Location\Controllers\Location::delete');
});

    