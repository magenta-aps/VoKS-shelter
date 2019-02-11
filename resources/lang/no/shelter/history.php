<?php

return [
    'push' => [
        'title' => 'Push-melding',
        'result' => 'Melding<br/><strong>:count/:total</strong>'
    ],
    'sms' => [
        'title' => 'SMS',
        'result' => 'Sendt til<br/><strong>:count/:total</strong>'
    ],
    'audio' => [
        'trigger' => [
            'title' => 'Talemelding',
            'message' => 'Spilte av <strong>:voice</strong> til <strong>:group</strong>',
            'default' => [
                'voice' => 'N/A',
                'group' => 'N/A'
            ],
            'result' => [
                'error' => 'Feilet',
                'success' => 'Avspilt'
            ]
        ],
        'play' => [
            'title' => 'Talemelding',
            'message' => 'Spilte av meldingen <strong>:voice</strong> til sonen <strong>:group</strong>',
            'default' => [
                'voice' => 'N/A',
                'group' => 'N/A'
            ],
            'result' => [
                'error' => 'Feilet',
                'success' => 'Avspilt'
            ]
        ],
        'live' => [
            'title' => 'Direkte talemelding',
            'message' => 'Ringte fra <strong>:number</strong> og talte ut direkte talemelding til <strong>:group</strong>',
            'default' => [
                'group' => 'N/A'
            ],
            'result' => [
                'error' => 'Feilet',
                'success' => 'Avspilt'
            ]
        ]
    ]
];