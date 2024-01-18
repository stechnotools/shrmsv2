<?php
namespace Admin\Report\Config;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
   $routes->add('component', 'Report\Controllers\Component::index');
   $routes->add('mcomponent', 'Report\Controllers\MComponent::index');
   $routes->add('mactivityyear', 'Report\Controllers\MActivity::index');
   $routes->add('mvcluster', 'Report\Controllers\MActivity::mvcluster');
   $routes->add('mactivitycount', 'Report\Controllers\MActivity::mactivitycount');

});
