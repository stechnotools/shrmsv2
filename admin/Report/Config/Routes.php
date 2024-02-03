<?php
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->group('report', function($routes){
        $routes->add('attendance', 'Report\Controllers\Attendance::index');
        $routes->add('payroll', 'Report\Controllers\Payroll::index');
    });
});
