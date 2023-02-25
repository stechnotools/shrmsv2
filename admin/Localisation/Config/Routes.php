<?php
namespace Admin\Localisation\Config;
if(!isset($routes))
{
    $routes = \Config\Services::routes(true);
}
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('country', 'Localisation\Controllers\Country::index');
    $routes->post('country/search','Localisation\Controllers\Country::search');
    $routes->add('country/state/','Localisation\Controllers\Country::state/');
    $routes->get('country/state/(:segment)','Localisation\Controllers\Country::state/$1');
    $routes->match(['get','post'],'country/add', 'Localisation\Controllers\Country::add');
    $routes->match(['get','post'],'country/edit/(:segment)', 'Localisation\Controllers\Country::edit/$1');
    $routes->get('country/delete/(:segment)',   'Localisation\Controllers\Country::delete/$1');
    $routes->post('country/delete','Localisation\Controllers\Country::delete');


    $routes->add('village', 'Localisation\Controllers\Village::index');
    $routes->post('village/search','Localisation\Controllers\Village::search');
    $routes->match(['get','post'],'village/add', 'Localisation\Controllers\Village::add');
    $routes->match(['get','post'],'village/edit/(:segment)', 'Localisation\Controllers\Village::edit/$1');
    $routes->get('village/delete/(:segment)',   'Localisation\Controllers\Village::delete/$1');
    $routes->post('village/delete','Localisation\Controllers\Village::delete');

    $routes->add('cluster', 'Localisation\Controllers\Cluster::index');
    $routes->post('cluster/search','Localisation\Controllers\Cluster::search');
    $routes->get('cluster/grampanchayat/(:segment)','Localisation\Controllers\Cluster::grampanchayat/$1');
    $routes->match(['get','post'],'cluster/add', 'Localisation\Controllers\Cluster::add');
    $routes->match(['get','post'],'cluster/edit/(:segment)', 'Localisation\Controllers\Cluster::edit/$1');
    $routes->get('cluster/delete/(:segment)',   'Localisation\Controllers\Cluster::delete/$1');
    $routes->post('cluster/delete','Localisation\Controllers\Cluster::delete');
});
