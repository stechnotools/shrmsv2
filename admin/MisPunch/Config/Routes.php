<?php
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('mispunch/request', 'MisPunch\Controllers\MisPunch::request');
    $routes->post('mispunch/search','MisPunch\Controllers\MisPunch::search');
    $routes->add('mispunch/requestpop', 'MisPunch\Controllers\MisPunch::requestPop');


    $routes->add('mispunch/approval', 'MisPunch\Controllers\MisPunch::approval');
    $routes->add('mispunch/approvalpop/(:segment)','MisPunch\Controllers\MisPunch::approvalPop/$1');

});

