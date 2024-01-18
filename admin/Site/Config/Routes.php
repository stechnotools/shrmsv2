<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('site', 'Site\Controllers\Site::index');
    $routes->post('site/search','Site\Controllers\Site::search');
    $routes->match(['get','post'],'site/add', 'Site\Controllers\Site::add');
    $routes->match(['get','post'],'site/edit/(:segment)', 'Site\Controllers\Site::edit/$1');
    $routes->get('site/delete/(:segment)',   'Site\Controllers\Site::delete/$1');
    $routes->post('site/delete','Site\Controllers\Site::delete');
});

    