<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('section', 'Section\Controllers\Section::index');
    $routes->post('section/search','Section\Controllers\Section::search');
    $routes->match(['get','post'],'section/add', 'Section\Controllers\Section::add');
    $routes->match(['get','post'],'section/edit/(:segment)', 'Section\Controllers\Section::edit/$1');
    $routes->get('section/delete/(:segment)',   'Section\Controllers\Section::delete/$1');
    $routes->post('section/delete','Section\Controllers\Section::delete');
});

    