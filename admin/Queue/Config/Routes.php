<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('queue', 'Queue\Controllers\Queue::index');
    $routes->post('queue/search','Queue\Controllers\Queue::search');
    $routes->match(['get','post'],'queue/add', 'Queue\Controllers\Queue::add');
    $routes->match(['get','post'],'queue/edit/(:segment)', 'Queue\Controllers\Queue::edit/$1');
    $routes->get('queue/delete/(:segment)',   'Queue\Controllers\Queue::delete/$1');
    $routes->post('queue/delete','Queue\Controllers\Queue::delete');
    $routes->get('queue/history',   'Queue\Controllers\Queue::history');
    $routes->get('queue/deletehistory/(:segment)',   'Queue\Controllers\Queue::deleteHistory/$1');
});

