<?php
return [
    'save' => 'Save',
    'cancel'    => 'Cancel',
    'remove'    => 'Remove',
    'edit'      => 'Edit',
    'menu' => [
        'main' => [
            'general' => 'General Settings',
            'school' => 'Shelters management',
            'buttons' => 'IP Buttons',
            'map' => 'Map',
        ],
        'general' => [
            'system' => 'System',
            'help' => 'Help defaults',
            'push' => 'Notification defaults',
            'sms' => 'SMS texts defaults'
        ],
    ],
    'contents' => [
        'system' => [
            'title' => 'System',
            'description' => 'Default system settings',
            'timezone' => 'Default timezone',
            'language' => 'Default language',
            'reconnect' => 'Default Audio reconnect time',
            'ordering' => 'Default ordering in waiting line',
            'sms' => 'SMS provider',
            'phone' => 'Phone system provider',
            'user' => 'User data source',
            'device' => 'Device location source'
        ],
		'sources' => [
			'ad'		=> 'Active Directory',
			'ale'		=> 'Aruba Ale',
      'aruba'		=> 'Aruba',
			'cisco'		=> 'Cisco CMX',
			'google'	=> 'Google Maps',
		],
    'push' => [
        'title' => 'Notifications',
        'description' => 'Setup default notification settings',
        'new' => 'New Default Notification',
        'table' => [
            'label' => 'Notification Label',
            'content' => 'Notification Content',
            'options' => 'Options',
        ]
    ],
    'sms' => [
        'title' => 'SMS',
        'description' => 'Setup default sms messages when alarm is triggered',
        'trigger' => 'Initial alarm triggering message',
        'information' => 'Crisis center information message',
        'symbols' => 'Symbols left:'
    ],
    'school' => [
        'title' => 'Setup Schools',
        'description' => 'Manage school settings',
        'sync' => 'Sync schools',
        'add' => 'Add new',
        'table' => [
            'name' => 'School name',
            'mac' => 'Shelter PC MAC',
            'ip' => 'Shelter PC IP',
            'ad' => 'Active Directory ID',
            'phone' => 'Phone System ID',
            'options' => 'Options',
            'url' => 'Url',
            'police_number' => 'Police number',
            'use_gps' => 'Use GPS',
            'display' => 'Display',
            'public' => 'Public',
            'controller' => 'Controller',
            'version' => 'Version'
        ]
    ],
    'maps' => [
        'title' => 'Maps',
        'description' => 'Sync and preview maps',
        'sync' => 'Sync Maps'
    ],
    'defaults'  => [
      'none'      => 'None'
    ]
  ]
];