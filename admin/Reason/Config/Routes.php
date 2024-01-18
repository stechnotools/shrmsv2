<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('reason', 'Reason\Controllers\Reason::index');
    $routes->post('reason/search','Reason\Controllers\Reason::search');
    $routes->match(['get','post'],'reason/add', 'Reason\Controllers\Reason::add');
    $routes->match(['get','post'],'reason/edit/(:segment)', 'Reason\Controllers\Reason::edit/$1');
    $routes->get('reason/delete/(:segment)',   'Reason\Controllers\Reason::delete/$1');
    $routes->post('reason/delete','Reason\Controllers\Reason::delete');
});

    