<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('mispunch', 'MisPunch\Controllers\MisPunch::index');
    $routes->post('mispunch/search','MisPunch\Controllers\MisPunch::search');
    $routes->match(['get','post'],'mispunch/add', 'MisPunch\Controllers\MisPunch::add');
    $routes->match(['get','post'],'mispunch/edit/(:segment)', 'MisPunch\Controllers\MisPunch::edit/$1');
    $routes->get('mispunch/delete/(:segment)',   'MisPunch\Controllers\MisPunch::delete/$1');
    $routes->post('mispunch/delete','MisPunch\Controllers\MisPunch::delete');
    $routes->get('mispunch/history',   'MisPunch\Controllers\MisPunch::history');
    $routes->get('mispunch/deletehistory/(:segment)',   'MisPunch\Controllers\MisPunch::deleteHistory/$1');
    $routes->add('mispunch/request', 'MisPunch\Controllers\MisPunch::request');
    $routes->post('mispunch/approve','MisPunch\Controllers\MisPunch::approve');

});

