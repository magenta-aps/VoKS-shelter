<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
	'enabled'		=> env('AD_ENABLED', true),
    'connection'    => [
        //'user_id_key' => 'samaccountname',
        //'person_filter' => array('category' => 'objectCategory', 'person' => 'person'),
        'account_suffix'     => env('AD_ACCOUNT_SUFFIX'),
        'base_dn'            => env('AD_DN'),
        'domain_controllers' => [env('AD_HOST')],
        'admin_username'     => env('AD_USERNAME'),
        'admin_password'     => env('AD_PASSWORD'),
        'real_primarygroup'  => true,
        'use_ssl'            => false,
        'use_tls'            => false,
        'recursive_groups'   => true,
        'ad_port'            => 389,
        'sso'                => false
    ],
    'fields'        => [
        'displayname', //full name
        'telephonenumber',  // phone number
        'mobile',
        'samaccountname',  // username
        'mail', //email
        'userprincipalname', //account email in AD
        'company' // which user belongs to which crisis team
    ],
    'field-map'     => [
        'phone' => 'mobile',
        'name'  => 'displayname',
        'email' => 'mail'
    ],
    'default-group' => env('AD_GROUP')
];
