<?php
return [
    'save' => 'Save',
    'tabs' => [
        'general' => 'General settings',
        'help' => 'Help',
        'sms' => 'Information SMS management',
        'phone' => 'Phone System',
        'push' => 'Push notification management',
        'buttons' => 'IP buttons',
        'reports' => 'Reports',
        'team' => 'Crisis center list',
        'reset' => 'Reset Shelter'
    ],
    'general' => [
        'title' => 'General settings',
        'description' => 'Setup general settings',
        'labels' => [
            'timezone' => 'Default timezone',
            'language' => 'Default language',
            'reconnect' => 'Default reconnect',
            'ordering' => 'Default ordering in waiting line'
        ],
        'save' => 'Save'
    ],
    'api' => [
        'title' => 'API Configuration',
        'description' => 'Explanation text about api configuration',
        'labels' => [
            'sms_provider' => 'SMS provider',
            'phone_system_provider' => 'Phone system provider',
            'user_data_source' => 'User data source',
            'client_data_source' => 'Device location source'
        ]
    ],
    'buttons' => [
        'title' => 'Buttons',
        'description' => 'Set up IP buttons',
        'add' => 'Add Button',
        'table' => [
            'number' => 'Button number',
            'name' => 'Button name',
            'cbf' => 'Campus, Building, Floor',
            'mac' => 'Shelter PC MAC',
            'ip' => 'Shelter PC IP',
            'x' => 'X',
            'y' => 'Y',
            'options' => 'Options'
        ],
        'placeholder' => 'Please select'

    ],
    'reports' => [
        'title' => 'Log',
        'description' => 'Log of events in BCS',
        'table' => [
            'created_at' => 'Time',
            'log_type' => 'Log type',
            'device_type' => 'Device type',
            'device_id' => 'Device ID',
            'fullname' => 'Full name',
            'mac_address' => 'MAC address',
            'floor_id' => 'Floor ID',
            'log_types' => [
                'alarm_triggered' => 'Alarm triggered',
                'unknown_type' => 'Unknown log type'
            ]
        ]
    ],
    'maps' => [
        'title' => 'Maps',
        'description' => 'Explanation about description',
        'sync' => 'Sync manually'
    ],
    'push' => [
        'title' => 'Push notifications',
        'description' => 'Manage push notification templates',
        'button' => [
            'new' => 'New Default Notification',
            'import' => 'Import defaults',
            'show' => 'Show',
            'hide' => 'Hide'
        ],
        'table' => [
            'label' => 'Notification Label',
            'content' => 'Notification Content',
            'options' => 'Options',
        ]
    ],
    'sms' => [
        'title' => 'SMS Messages',
        'description' => 'Setup sms messages, which will be sent on alarm trigger.',
        'trigger' => 'Initial alarm triggering message',
        'symbols' => 'Symbols left:',
        'default' => 'Default message:',
        'information' => 'Crisis center information message',
        'save' => 'Save',
    ],
    'team' => [
        'title' => 'Crisis Center',
        'description' => 'Synchronize and preview crisis center members',
        'button' => [
            'sync' => 'Sync members',
        ],
        'table' => [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone number'
        ]
    ],
];