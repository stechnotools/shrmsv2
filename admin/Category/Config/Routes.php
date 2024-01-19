<?php
namespace Admin\Common\Config;
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('category', 'Category\Controllers\Category::index');
    $routes->post('category/search','Category\Controllers\Category::search');
    $routes->match(['get','post'],'category/add', 'Category\Controllers\Category::add');
    $routes->match(['get','post'],'category/edit/(:segment)', 'Category\Controllers\Category::edit/$1');
    $routes->get('category/delete/(:segment)',   'Category\Controllers\Category::delete/$1');
    $routes->post('category/delete','Category\Controllers\Category::delete');
});
