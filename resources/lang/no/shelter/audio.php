<?php

return [
    'play' => [
        'field' => [
            'voice' => [
                'placeholder' => 'Velg en predefinert talemelding'
            ],
            'group' => [
                'placeholder' => 'Velg sone meldingen skal sendes til'
            ],
            'submit' => 'Send talemeldingen'
        ],
        'toast' => [
            'success' => [
                'title' => '(NO) Success',
                'message' => '(NO) Voice message has been played successfully.'
            ],
            'error' => [
                'playback' => [
                    'title' => '(NO) Playback error',
                    'message' => '(NO) Error playing voice message to selected group.'
                ],
                'invalid' => [
                    'title' => 'Feil',
                    'message' => '(NO) Invalid voice message or group provided.'
                ]
            ]
        ]
    ],
    'live' => [
        'heading' => 'Tal ut en direkte talemelding',
        'field' => [
            'group' => [
                'placeholder' => 'Velg sone meldingen skal sendes til'
            ],
            'number' => [
                'label' => 'Under ser du internummeret på den telefonen du skal bruke for å tale ut en direkte talemelding. Du kan endre dette ved å markere nummeret og skrive inn et annet internummer.',
                'placeholder' => 'Telefonnummer'
            ],
            'submit' => 'Initier en direkte talemelding'
        ],
        'toast' => [
            'success' => [
                'title' => '(NO) Success',
                'message' => '(NO) Phone number has been called successfully.'
            ],
            'error' => [
                'playback' => [
                    'title' => '(NO) Playback error',
                    'message' => '(NO) Error making the call.'
                ],
                'invalid' => [
                    'title' => 'Feil',
                    'message' => '(NO) Invalid phone number or group provided.'
                ]
            ]
        ]
    ]
];