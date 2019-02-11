<?php

return [
    'play' => [
        'field' => [
            'voice' => [
                'placeholder' => 'Select audio message'
            ],
            'group' => [
                'placeholder' => 'Select device group'
            ],
            'submit' => 'Play to devices'
        ],
        'toast' => [
            'success' => [
                'title' => 'Success',
                'message' => 'Voice message has been played successfully.'
            ],
            'error' => [
                'playback' => [
                    'title' => 'Playback error',
                    'message' => 'Error playing voice message to selected group.'
                ],
                'invalid' => [
                    'title' => 'Error',
                    'message' => 'Invalid voice message or group provided.'
                ]
            ]
        ]
    ],
    'live' => [
        'heading' => 'Broadcast new audio message',
        'field' => [
            'group' => [
                'placeholder' => 'Select device group'
            ],
            'number' => [
                'label' => 'Please provide an internal phone number you will use to broadcast a live message.',
                'placeholder' => 'Phone number'
            ],
            'submit' => 'Start broadcasting'
        ],
        'toast' => [
            'success' => [
                'title' => 'Success',
                'message' => 'Phone number has been called successfully.'
            ],
            'error' => [
                'playback' => [
                    'title' => 'Playback error',
                    'message' => 'Error making the call.'
                ],
                'invalid' => [
                    'title' => 'Error',
                    'message' => 'Invalid phone number or group provided.'
                ]
            ]
        ]
    ]
];