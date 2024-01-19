<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('designation', 'Designation\Controllers\Designation::index');
    $routes->post('designation/search','Designation\Controllers\Designation::search');
    $routes->match(['get','post'],'designation/add', 'Designation\Controllers\Designation::add');
    $routes->match(['get','post'],'designation/edit/(:segment)', 'Designation\Controllers\Designation::edit/$1');
    $routes->get('designation/delete/(:segment)',   'Designation\Controllers\Designation::delete/$1');
    $routes->post('designation/delete','Designation\Controllers\Designation::delete');
});

