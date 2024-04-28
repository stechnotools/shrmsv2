<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('leave', 'Leave\Controllers\Leave::index');
    $routes->post('leave/search','Leave\Controllers\Leave::search');
    $routes->match(['get','post'],'leave/add', 'Leave\Controllers\Leave::add');
    $routes->match(['get','post'],'leave/edit/(:segment)', 'Leave\Controllers\Leave::edit/$1');
    $routes->get('leave/delete/(:segment)',   'Leave\Controllers\Leave::delete/$1');
    $routes->post('leave/delete','Leave\Controllers\Leave::delete');

    $routes->add('leave/application', 'Leave\Controllers\Application::index');
    $routes->post('leave/application/search','Leave\Controllers\Application::search');
    $routes->match(['get','post'],'leave/application/add', 'Leave\Controllers\Application::add');
    $routes->match(['get','post'],'leave/application/edit/(:segment)', 'Leave\Controllers\Application::edit/$1');
    $routes->get('leave/application/delete/(:segment)',   'Leave\Controllers\Application::delete/$1');
    $routes->post('leave/application/delete','Leave\Controllers\Application::delete');
    $routes->post('leave/application/getLeaveDetails','Leave\Controllers\Application::getLeaveDetails');

    $routes->add('leave/opening', 'Leave\Controllers\Opening::index');
    $routes->post('leave/opening/search','Leave\Controllers\Opening::search');
    $routes->match(['get','post'],'leave/opening/add', 'Leave\Controllers\Opening::add');
    $routes->match(['get','post'],'leave/opening/edit/(:segment)', 'Leave\Controllers\Opening::edit/$1');
    $routes->get('leave/opening/delete/(:segment)',   'Leave\Controllers\Opening::delete/$1');
    $routes->post('leave/opening/delete','Leave\Controllers\Opening::delete');

    $routes->add('leave/approval', 'Leave\Controllers\Approval::index');
    $routes->post('leave/approval/search','Leave\Controllers\Approval::search');
    $routes->post('leave/approval/action/(:segment)','Leave\Controllers\Approval::action/$1');


});

