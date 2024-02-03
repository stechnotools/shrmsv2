<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('attendance', 'Attendance\Controllers\Attendance::index');
    $routes->post('attendance/search','Attendance\Controllers\Attendance::search');
    $routes->match(['get','post'],'bank/add', 'Bank\Controllers\Bank::add');
    $routes->match(['get','post'],'bank/edit/(:segment)', 'Bank\Controllers\Bank::edit/$1');
    $routes->get('bank/delete/(:segment)',   'Bank\Controllers\Bank::delete/$1');
    $routes->post('bank/delete','Bank\Controllers\Bank::delete');
});
