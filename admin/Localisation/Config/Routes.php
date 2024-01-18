<?php
namespace Admin\Localisation\Config;
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

    $routes->add('state', 'Localisation\Controllers\State::index');
    $routes->post('state/search','Localisation\Controllers\State::search');
    $routes->add('state/city/','Localisation\Controllers\State::city/');
    $routes->get('state/city/(:segment)','Localisation\Controllers\State::city/$1');
    $routes->match(['get','post'],'state/add', 'Localisation\Controllers\State::add');
    $routes->match(['get','post'],'state/edit/(:segment)', 'Localisation\Controllers\State::edit/$1');
    $routes->get('state/delete/(:segment)',   'Localisation\Controllers\State::delete/$1');
    $routes->post('state/delete','Localisation\Controllers\State::delete');

    $routes->add('city', 'Localisation\Controllers\City::index');
    $routes->post('city/search','Localisation\Controllers\City::search');
    $routes->match(['get','post'],'city/add', 'Localisation\Controllers\City::add');
    $routes->match(['get','post'],'city/edit/(:segment)', 'Localisation\Controllers\City::edit/$1');
    $routes->get('city/delete/(:segment)',   'Localisation\Controllers\City::delete/$1');
    $routes->post('city/delete','Localisation\Controllers\City::delete');
});
