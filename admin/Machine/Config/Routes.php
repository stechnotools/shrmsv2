<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('machine', 'Machine\Controllers\Machine::index');
    $routes->post('machine/search','Machine\Controllers\Machine::search');
    $routes->match(['get','post'],'machine/add', 'Machine\Controllers\Machine::add');
    $routes->match(['get','post'],'machine/edit/(:segment)', 'Machine\Controllers\Machine::edit/$1');
    $routes->get('machine/delete/(:segment)',   'Machine\Controllers\Machine::delete/$1');
    $routes->post('machine/delete','Machine\Controllers\Machine::delete');
});

    