<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('branch', 'Branch\Controllers\Branch::index');
    $routes->post('branch/search','Branch\Controllers\Branch::search');
    $routes->match(['get','post'],'branch/add', 'Branch\Controllers\Branch::add');
    $routes->match(['get','post'],'branch/edit/(:segment)', 'Branch\Controllers\Branch::edit/$1');
    $routes->get('branch/delete/(:segment)',   'Branch\Controllers\Branch::delete/$1');
    $routes->post('branch/delete','Branch\Controllers\Branch::delete');
});