<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('employee', 'Employee\Controllers\Employee::index');
    $routes->post('employee/search','Employee\Controllers\Employee::search');
    $routes->match(['get','post'],'employee/add', 'Employee\Controllers\Employee::add');
    $routes->match(['get','post'],'employee/edit/(:segment)', 'Employee\Controllers\Employee::edit/$1');
    $routes->get('employee/delete/(:segment)',   'Employee\Controllers\Employee::delete/$1');
    $routes->post('employee/delete','Employee\Controllers\Employee::delete');
    $routes->post('employee/upload','Employee\Controllers\Employee::upload');
    $routes->post('employee/uploademp','Employee\Controllers\Employee::uploademp');
});