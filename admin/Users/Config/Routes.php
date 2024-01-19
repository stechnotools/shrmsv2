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

    $routes->add('roles', 'Users\Controllers\UserGroup::index');
    $routes->post('roles/search','Users\Controllers\UserGroup::search');
    $routes->match(['get','post'],'roles/add', 'Users\Controllers\UserGroup::add');
    $routes->match(['get','post'],'roles/edit/(:segment)', 'Users\Controllers\UserGroup::edit/$1');
    $routes->get('roles/delete/(:segment)',   'Users\Controllers\UserGroup::delete/$1');
    $routes->post('roles/delete','Users\Controllers\UserGroup::delete');
    $routes->match(['get','post'],'roles/permission/(:segment)', 'Users\Controllers\UserGroup::permission/$1');
});
