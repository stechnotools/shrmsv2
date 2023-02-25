<?php
namespace Admin\Setting\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('setting', 'Setting\Controllers\Setting::index');
    $routes->get('setting/serverinfo', 'Setting\Controllers\Setting::serverinfo');
    $routes->add('setting/dashboard','Setting\Controllers\Setting::dashboard');
    $routes->post('setting/save_dashboard/(:segment)/(:segment)', 'Setting\Controllers\Setting::save_dashboard/$1/$2');
    $routes->add('msubmission','Setting\Controllers\Msubmission::index');

    $routes->get('msubmission/getForms/(:segment)', 'Setting\Controllers\Msubmission::getForms/$1');
    /*$routes->get('proceeding/delete/(:segment)',   'Proceeding\Controllers\Proceeding::delete/$1');
    $routes->post('proceeding/delete','Proceeding\Controllers\Proceeding::delete');*/

});

?>
    