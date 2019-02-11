<?php

return [
    'alarm' => [
        'title' => 'Alarm trigger',
        'desc' => 'Set crisis message that is going to be played to the crisis center when Alarm is triggered',
        'field' => [
            'group' => [
                'label' => 'Crisis center',
                'value' => 'Please select crisis center'
            ],
            'media' => [
                'label' => 'Crisis message',
                'value' => 'Please select crisis message'
            ],
            'interrupt' => [
                'label' => 'Choose message to the other part in a call',
                'value' => 'None'
            ],
            'save' => [
                'label' => 'Save alarm trigger settings'
            ]
        ]
    ],
    'broadcast' => [
        'title' => 'Live broadcast',
        'desc' => 'Set live broadcast default phone number',
        'field' => [
            'number' => [
                'label' => 'Phone number for live broadcasting'
            ],
            'save' => [
                'label' => 'Save phone number'
            ]
        ]
    ]
];
