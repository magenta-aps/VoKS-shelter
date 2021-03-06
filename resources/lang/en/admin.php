<?php
return [
    'save' => 'Save',
    'tabs' => [
        'general' => 'General settings',
        'help' => 'Help',
        'sms' => 'Information SMS management',
        'phone' => 'Phone System',
        'push' => 'Notification management',
        'buttons' => 'IP buttons',
        'reports' => 'Reports',
        'logs' => 'Logs',
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
    'logs' => [
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
            'x' => 'X',
            'y' => 'Y',
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
        'title' => 'Notifications',
        'description' => 'Manage notification templates',
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
    'reports' => [
        'title' => 'Reports',
        'description' => 'Reports of events in BCS',
        'no_results' => 'No reports found matching the search criteria.',
        'filters' => [
            'date_range' => 'Date range',
            'false_alarm' => [
                'all' => 'All alarms',
                'exclude_false' => 'Exclude false alarms',
                'only_false' => 'Only show false alarms'
            ]
        ],
        'table' => [
            'triggered_at' => 'Time',
            'device_type' => 'Device type',
            'device_id' => 'Device ID',
            'fullname' => 'Full name',
            'duration' => 'Duration',
            'push_notifications' => 'Push notifications',
            'video_chats' => 'Video chats',
            'download_log' => 'Download log',
            'download_report' => 'Download report',
            'download_csv' => 'Get .csv',
            'download_pdf' => 'Get .pdf',
            'false_alarm' => "False alarm?",
            'note' => 'Note',
            'yes' => 'Yes',
            'no' => 'No'
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