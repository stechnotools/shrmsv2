<?php
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('punch', 'Punch\Controllers\Punch::index');
    $routes->post('punch/search','Punch\Controllers\Punch::search');
    $routes->match(['get','post'],'punch/add', 'Punch\Controllers\Punch::add');
    $routes->match(['get','post'],'punch/edit/(:segment)', 'Punch\Controllers\Punch::edit/$1');
    $routes->get('punch/delete/(:segment)',   'Punch\Controllers\Punch::delete/$1');
    $routes->post('punch/delete','Punch\Controllers\Punch::delete');
});

    