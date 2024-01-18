<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('workorder', 'Workorder\Controllers\Workorder::index');
    $routes->post('workorder/search','Workorder\Controllers\Workorder::search');
    $routes->match(['get','post'],'workorder/add', 'Workorder\Controllers\Workorder::add');
    $routes->match(['get','post'],'workorder/edit/(:segment)', 'Workorder\Controllers\Workorder::edit/$1');
    $routes->get('workorder/delete/(:segment)',   'Workorder\Controllers\Workorder::delete/$1');
    $routes->post('workorder/delete','Workorder\Controllers\Workorder::delete');
});

    