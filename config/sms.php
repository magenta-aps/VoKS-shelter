<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */


/**
 * Simple-SMS
 * Simple-SMS is a package made for Laravel to send/receive (polling/pushing) text messages.
 *
 * @link http://www.simplesoftware.io
 * @author SimpleSoftware support@simplesoftware.io
 *
 */

/*
    |--------------------------------------------------------------------------
    | Simple SMS
    |--------------------------------------------------------------------------
    | Driver
    |   Email:  The Email driver uses the Illuminate\Mail\Mailer instance to
    |           send SMS messages based on the carriers e-mail to SMS gateways.
    |           The Email driver will send messages out based on your Laravel
    |           mail settings.
    |   Twilio: Twilio is an SMS service that sends messages at a affordable
    |           and reliable rate.
    |--------------------------------------------------------------------------
    | From
    |   Email:  The from address must be a valid email address.
    |   Twilio: The from address must be a verified phone number within Twilio.
    |--------------------------------------------------------------------------
    | Twilio Additional Settings
    |   Account SID:  The Account SID associated with your Twilio account.
    |   Auth Token:   The Auth Token associated with your Twilio account.
    |   Verify:       Ensures extra security by checking if requests
    |                 are really coming from Twilio.
    |
    |--------------------------------------------------------------------------
    | EZTexting Additional Settings
    |   Username:  Your login username.
    |   Password:  Your login password.
    |--------------------------------------------------------------------------
    | CallFire
    |   App Login:     Your login settings. (https://www.callfire.com/ui/manage/access)
    |   App Password:  Your login password. (https://www.callfire.com/ui/manage/access)
    |--------------------------------------------------------------------------
    | Mozeo
    | Company Key:  Your company key. (https://www.mozeo.com/mozeo/customer/platformdetails.php)
    | Username:     Your username.  (https://www.mozeo.com/mozeo/customer/platformdetails.php)
    | Password:     Your password.  (https://www.mozeo.com/mozeo/customer/platformdetails.php)

*/

return [
    'enabled' => env('SMS_ENABLED', false),
    'from' => env('SMS_FROM', ''),
    'vianett' => [
      'url' => env('SMS_VIANETT_URL', ''),
      'username' => env('SMS_VIANETT_USERNAME', ''),
      'password' => env('SMS_VIANETT_PASSWORD', '')
    ],
    'talariax' => [
        'url' => env('SMS_TARALIAX_URL', ''),
        'mode' => env('SMS_TARALIAX_MODE', 'utf'),
    ],
    'twilio' => [
        'account_sid' => 'Your SID',
        'auth_token' => 'Your Token',
        'verify' => true,
    ],
    'eztexting' => [
        'username' => 'Your EZTexting Username',
        'password' => 'Your EZTexting Password'
    ],
    'callfire' => [
        'app_login' => 'Your CallFire API Login',
        'app_password' => 'Your CallFire API Password'
    ],
    'mozeo' => [
        'companyKey' => 'Your Mozeo Company Key',
        'username' => 'Your Mozeo Username',
        'password' => 'Your Mozeo Password'
    ]
];