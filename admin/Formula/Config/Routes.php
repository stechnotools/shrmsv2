<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('formula', 'Formula\Controllers\Formula::index');
    $routes->post('formula/search','Formula\Controllers\Formula::search');
    $routes->match(['get','post'],'formula/add', 'Formula\Controllers\Formula::add');
    $routes->match(['get','post'],'formula/edit/(:segment)', 'Formula\Controllers\Formula::edit/$1');
    $routes->get('formula/delete/(:segment)',   'Formula\Controllers\Formula::delete/$1');
    $routes->post('formula/delete','Formula\Controllers\Formula::delete');


    $routes->add('formula/field', 'Formula\Controllers\Field::index');
    $routes->post('formula/field/search','Formula\Controllers\Field::search');
    $routes->match(['get','post'],'formula/field/add', 'Formula\Controllers\Field::add');
    $routes->match(['get','post'],'formula/field/edit/(:segment)', 'Formula\Controllers\Field::edit/$1');
    $routes->get('formula/field/delete/(:segment)',   'Formula\Controllers\Field::delete/$1');
    $routes->post('formula/field/delete','Formula\Controllers\Field::delete');
});

