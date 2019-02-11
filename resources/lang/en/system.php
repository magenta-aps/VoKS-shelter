<?php
return [
    'save' => 'Save',
    'menu' => [
        'main' => [
            'general' => 'General Settings',
            'school' => 'School Management',
            'buttons' => 'IP Buttons',
            'map' => 'Map',
        ],
        'general' => [
            'system' => 'System',
            'help' => 'Help defaults',
            'push' => 'Push notification defaults',
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
            'device' => 'Device data source'
        ],
        'push' => [
            'title' => 'Push notifications',
            'description' => 'Setup default push notification settings',
            'new' => 'New Defaut Notification',
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
            'table' => [
                'name' => 'School name',
                'mac' => 'Shelter PC MAC',
                'ip' => 'Shelter PC IP',
                'ad' => 'Active Directory ID',
                'phone' => 'Phone System ID',
                'options' => 'Options'
            ]
        ],
        'maps' => [
            'title' => 'Maps',
            'description' => 'Sync and preview maps',
            'sync' => 'Sync Maps'
        ]
    ]

];