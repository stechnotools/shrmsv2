<?php
$routes->group(env('app.adminROUTE'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->group('report', function($routes){
        $routes->add('attendance', 'Report\Controllers\Attendance::index');
        $routes->add('attendance/earlyarrival', 'Report\Controllers\Attendance::earlyarrival');
        $routes->add('attendance/latearrival', 'Report\Controllers\Attendance::latearrival');
        $routes->add('attendance/absenteesism', 'Report\Controllers\Attendance::absenteesism');
        $routes->add('attendance/dattendance', 'Report\Controllers\Attendance::dattendance');
        $routes->add('attendance/dperformance', 'Report\Controllers\Attendance::dperformance');
        $routes->add('attendance/mperformance', 'Report\Controllers\Attendance::mperformance');
        $routes->add('attendance/musterroll', 'Report\Controllers\Attendance::musterroll');
        $routes->add('attendance/mearlyarrival', 'Report\Controllers\Attendance::mearlyarrival');
        $routes->add('attendance/mlatearrival', 'Report\Controllers\Attendance::mlatearrival');
        $routes->add('attendance/mpenalty', 'Report\Controllers\Attendance::mpenalty');
        $routes->add('attendance/roster', 'Report\Controllers\Attendance::roster');

        $routes->add('payroll', 'Report\Controllers\Payroll::index');
    });
});
