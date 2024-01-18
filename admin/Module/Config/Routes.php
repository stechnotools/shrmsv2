<?php
namespace Admin\Module\Config;
if(!isset($routes))
{
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('module', 'Module\Controllers\Module::index');
    $routes->post('module/search','Module\Controllers\Module::search');
    $routes->match(['get','post'],'module/add', 'Module\Controllers\Module::add');
    $routes->match(['get','post'],'module/edit/(:segment)', 'Module\Controllers\Module::edit/$1');
    $routes->get('module/delete/(:segment)',   'Module\Controllers\Module::delete/$1');
    $routes->post('module/delete','Module\Controllers\Module::delete');

});
