<?php
namespace Admin\Common\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('/', 'Common\Controllers\Dashboard::index');
    $routes->add('login', 'Common\Controllers\Auth::login');
    $routes->get('logout', 'Common\Controllers\Auth::logout');
    $routes->add('relogin', 'Common\Controllers\Auth::reLogin');
    $routes->match(['get','post'],'account/password', 'Common\Controllers\Auth::password');
    $routes->add('error', 'Common\Controllers\Errors::index');

});

?>
    