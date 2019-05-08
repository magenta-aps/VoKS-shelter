<?php

return [
    'push' => [
        'title' => 'Push',
        'result' => 'Got it<br/><strong>:count/:total</strong>'
    ],
    'sms' => [
        'title' => 'SMS',
        'result' => 'Sent to<br/><strong>:count/:total</strong>'
    ],
    'email' => [
        'title' => 'Email',
        'result' => 'Sent to<br/><strong>:count/:total</strong>'
    ],
    'audio' => [
        'trigger' => [
            'title' => 'Audio',
            'message' => 'Play <strong>:voice</strong> to <strong>:group</strong> device group on alarm trigger.',
            'default' => [
                'voice' => 'N/A',
                'group' => 'N/A'
            ],
            'result' => [
                'error' => 'Error',
                'success' => 'Played'
            ]
        ],
        'play' => [
            'title' => 'Audio',
            'message' => 'Play <strong>:voice</strong> to <strong>:group</strong> device group.',
            'default' => [
                'voice' => 'N/A',
                'group' => 'N/A'
            ],
            'result' => [
                'error' => 'Error',
                'success' => 'Played'
            ]
        ],
        'live' => [
            'title' => 'Audio',
            'message' => 'Call <strong>:number</strong> and play to <strong>:group</strong> device group.',
            'default' => [
                'group' => 'N/A'
            ],
            'result' => [
                'error' => 'Error',
                'success' => 'Played'
            ]
        ]
    ]
];