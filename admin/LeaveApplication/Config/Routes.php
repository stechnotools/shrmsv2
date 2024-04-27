<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('leaveapplication', 'LeaveApplication\Controllers\LeaveApplication::index');
    $routes->post('leaveapplication/search','LeaveApplication\Controllers\LeaveApplication::search');
    $routes->match(['get','post'],'leaveapplication/add', 'LeaveApplication\Controllers\LeaveApplication::add');
    $routes->match(['get','post'],'leaveapplication/edit/(:segment)', 'LeaveApplication\Controllers\LeaveApplication::edit/$1');
    $routes->get('leaveapplication/delete/(:segment)',   'LeaveApplication\Controllers\LeaveApplication::delete/$1');
    $routes->post('leaveapplication/delete','LeaveApplication\Controllers\LeaveApplication::delete');
    $routes->post('leaveapplication/getLeaveDetails','LeaveApplication\Controllers\LeaveApplication::getLeaveDetails');
    

});

