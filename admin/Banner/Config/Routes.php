<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('banner', 'Banner\Controllers\Banner::index');
    $routes->post('banner/search','Banner\Controllers\Banner::search');
    $routes->match(['get','post'],'banner/add', 'Banner\Controllers\Banner::add');
    $routes->match(['get','post'],'banner/edit/(:segment)', 'Banner\Controllers\Banner::edit/$1');
    $routes->get('banner/delete/(:segment)',   'Banner\Controllers\Thematic::delete/$1');
    $routes->post('banner/delete','Banner\Controllers\Banner::delete');

});

?>
    