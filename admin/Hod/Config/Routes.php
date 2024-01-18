<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('hod', 'Hod\Controllers\Hod::index');
    $routes->post('hod/search','Hod\Controllers\Hod::search');
    $routes->match(['get','post'],'hod/add', 'Hod\Controllers\Hod::add');
    $routes->match(['get','post'],'hod/edit/(:segment)', 'Hod\Controllers\Hod::edit/$1');
    $routes->get('hod/delete/(:segment)',   'Hod\Controllers\Hod::delete/$1');
    $routes->post('hod/delete','Hod\Controllers\Hod::delete');
});

