<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('leave', 'Leave\Controllers\Leave::index');
    $routes->post('leave/search','Leave\Controllers\Leave::search');
    $routes->match(['get','post'],'leave/add', 'Leave\Controllers\Leave::add');
    $routes->match(['get','post'],'leave/edit/(:segment)', 'Leave\Controllers\Leave::edit/$1');
    $routes->get('leave/delete/(:segment)',   'Leave\Controllers\Leave::delete/$1');
    $routes->post('leave/delete','Leave\Controllers\Leave::delete');
});

