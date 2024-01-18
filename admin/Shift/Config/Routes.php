<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('shift', 'Shift\Controllers\Shift::index');
    $routes->post('shift/search','Shift\Controllers\Shift::search');
    $routes->match(['get','post'],'shift/add', 'Shift\Controllers\Shift::add');
    $routes->match(['get','post'],'shift/edit/(:segment)', 'Shift\Controllers\Shift::edit/$1');
    $routes->get('shift/delete/(:segment)',   'Shift\Controllers\Shift::delete/$1');
    $routes->post('shift/delete','Shift\Controllers\Shift::delete');
});

    