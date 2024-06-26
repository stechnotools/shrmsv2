<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('sfield', 'Salary\Controllers\SField::index');
    $routes->post('sfield/search','Salary\Controllers\SField::search');
    $routes->match(['get','post'],'sfield/add', 'Salary\Controllers\SField::add');
    $routes->match(['get','post'],'sfield/edit/(:segment)', 'Salary\Controllers\SField::edit/$1');
    $routes->get('sfield/delete/(:segment)',   'Salary\Controllers\SField::delete/$1');
    $routes->post('sfield/delete','Salary\Controllers\SField::delete');

    $routes->add('salary', 'Salary\Controllers\Salary::index');
    $routes->post('salary/search','Salary\Controllers\Salary::search');
    $routes->match(['get','post'],'salary/add', 'Salary\Controllers\Salary::add');
    $routes->match(['get','post'],'salary/edit/(:segment)', 'Salary\Controllers\Salary::edit/$1');
    $routes->get('salary/delete/(:segment)',   'Salary\Controllers\Salary::delete/$1');
    $routes->post('salary/delete','Salary\Controllers\Salary::delete');
});

