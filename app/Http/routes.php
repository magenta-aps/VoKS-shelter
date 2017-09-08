<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */


Route::get('/', 'ShelterController@getIndex');
Route::get('views/{partial?}', 'ViewProxyController@getIndex');
Route::get('client', 'ClientController@index');

Route::get('super-admin/login-check', 'LoginCheckController@check');

Route::group(
    ['prefix' => 'system'],
    function () {
        Route::controller('general', 'System\General\MainController');
        Route::controller('schools', 'System\Schools\MainController');
        Route::controller('buttons', 'System\Buttons\MainController');
        Route::controller('maps',    'System\Maps\MainController');
        Route::controller('test',    'System\Test\MainController');
    }
);

Route::group(
    ['prefix' => 'admin'],
    function () {
        Route::controller('general', 'Admin\GeneralController');
        Route::controller('help', 'Admin\HelpController');
        Route::controller('sms', 'Admin\SmsController');
        Route::controller('phone-system', 'Admin\PhoneSystemController');
        Route::controller('notifications', 'Admin\NotificationController');
        Route::controller('buttons', 'Admin\ButtonController');
        Route::controller('crisis-team', 'Admin\CrisisTeamController');
    }
);

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
    'download' => 'DownloadFileController',
    'maps'     => 'Maps\MainController'
]);

Route::get('log/websockets', 'Log\ViewerController@getWebsockets');
Route::get('log/laravel', 'Log\ViewerController@getLaravel');

// API
Route::group(
    ['prefix' => 'api'],
    function () {
        // IP API for websockets
        Route::controller('system/ip-whitelist', 'System\Api\IpWhitelistController');

        Route::controller('device', 'Api\DeviceController');
        Route::controller('voks', 'Api\DeviceController'); //Deprecated. Will be removed soon.
        Route::controller('bcs', 'Api\DeviceController'); // Temporary. Should be: BcsController.
        Route::match(['get', 'post'], 'shelter/coordinates', ['uses' => 'Api\ShelterController@getCoordinates']);
        Route::controller('shelter', 'Api\ShelterController');

        Route::group(
            ['prefix' => 'ps'],
            function () {
                Route::match(['get'], 'nodes', ['uses' => 'Api\PhoneSystemController@nodes']);
                Route::match(['get', 'post'], 'play', ['uses' => 'Api\PhoneSystemController@play']);
                Route::match(['get', 'post'], 'broadcast', ['uses' => 'Api\PhoneSystemController@broadcast']);
            }
        );
    }
);
