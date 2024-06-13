<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('mainpunch', 'MainPunch\Controllers\MainPunch::index');
    $routes->post('mainpunch/search','MainPunch\Controllers\MainPunch::search');
    $routes->match(['get','post'],'mainpunch/add', 'MainPunch\Controllers\MainPunch::add');
    $routes->match(['get','post'],'mainpunch/edit/(:segment)', 'MainPunch\Controllers\MainPunch::edit/$1');
    $routes->get('mainpunch/delete/(:segment)',   'MainPunch\Controllers\MainPunch::delete/$1');
    $routes->post('mainpunch/delete','MainPunch\Controllers\MainPunch::delete');
    $routes->get('mainpunch/history',   'MainPunch\Controllers\MainPunch::history');
    $routes->get('mainpunch/deletehistory/(:segment)',   'MainPunch\Controllers\MainPunch::deleteHistory/$1');

    $routes->post('mainpunch/uploadpunch','MainPunch\Controllers\MainPunch::uploadpunch');
    $routes->post('mainpunch/uploadpunchbyqueue','MainPunch\Controllers\MainPunch::uploadPunchByQueue');
});

