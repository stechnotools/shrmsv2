<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('grade', 'Grade\Controllers\Grade::index');
    $routes->post('grade/search','Grade\Controllers\Grade::search');
    $routes->match(['get','post'],'grade/add', 'Grade\Controllers\Grade::add');
    $routes->match(['get','post'],'grade/edit/(:segment)', 'Grade\Controllers\Grade::edit/$1');
    $routes->get('grade/delete/(:segment)',   'Grade\Controllers\Grade::delete/$1');
    $routes->post('grade/delete','Grade\Controllers\Grade::delete');
});

