<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('leaveopening', 'LeaveOpening\Controllers\LeaveOpening::index');
    $routes->post('leaveopening/search','LeaveOpening\Controllers\LeaveOpening::search');
    $routes->match(['get','post'],'leaveopening/add', 'LeaveOpening\Controllers\LeaveOpening::add');
    $routes->match(['get','post'],'leaveopening/edit/(:segment)', 'LeaveOpening\Controllers\LeaveOpening::edit/$1');
    $routes->get('leaveopening/delete/(:segment)',   'LeaveOpening\Controllers\LeaveOpening::delete/$1');
    $routes->post('leaveopening/delete','LeaveOpening\Controllers\LeaveOpening::delete');
});

