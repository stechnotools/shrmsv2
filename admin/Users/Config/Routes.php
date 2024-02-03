<?php
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('users', 'Users\Controllers\Users::index');
    $routes->post('users/search','Users\Controllers\Users::search');
    $routes->match(['get','post'],'users/add', 'Users\Controllers\Users::add');
    $routes->match(['get','post'],'users/edit/(:segment)', 'Users\Controllers\Users::edit/$1');
    $routes->get('users/delete/(:segment)',   'Users\Controllers\Users::delete/$1');
    $routes->post('users/delete','Users\Controllers\Users::delete');
    $routes->get('users/login/(:segment)','Users\Controllers\Users::login/$1');

    $routes->add('roles', 'Users\Controllers\UserRole::index');
    $routes->post('roles/search','Users\Controllers\UserRole::search');
    $routes->match(['get','post'],'roles/add', 'Users\Controllers\UserRole::add');
    $routes->match(['get','post'],'roles/edit/(:segment)', 'Users\Controllers\UserRole::edit/$1');
    $routes->get('roles/delete/(:segment)',   'Users\Controllers\UserRole::delete/$1');
    $routes->post('roles/delete','Users\Controllers\UserRole::delete');
    $routes->match(['get','post'],'roles/permission/(:segment)', 'Users\Controllers\UserRole::permission/$1');
});
