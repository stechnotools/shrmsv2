<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('department', 'Department\Controllers\Department::index');
    $routes->post('department/search','Department\Controllers\Department::search');
    $routes->match(['get','post'],'department/add', 'Department\Controllers\Department::add');
    $routes->match(['get','post'],'department/edit/(:segment)', 'Department\Controllers\Department::edit/$1');
    $routes->get('department/delete/(:segment)',   'Department\Controllers\Department::delete/$1');
    $routes->post('department/delete','Department\Controllers\Department::delete');
});

