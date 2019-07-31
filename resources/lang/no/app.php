<?php

return [
    'header' => [
        'menu' => [
            'main' => [
                'help' => 'Hjelp og dokumentasjon',
                'plan' => 'Se på kart hvor alarmen ble utløst',
                'stream' => 'Videostrømmer'
            ],
            'users' => [
                'help' => 'Hjelp',
                'settings' => 'Innstillinger'
            ]
        ]
    ],
    'stream' => [
        'add' => 'Legg til',
        'chat_with' => 'Chat med:',
        'conversation' => 'Dialog',
        'minimize' => 'Lukk denne videstrømmen',
        'close_chat' => 'Lukk denne chat’n',
        'police_called' => '112',
        'mobile' => [
            'battery_loading' => 'Batteristatus: Lader',
            'battery' => 'Batteristatus:',

        ],
        'placeholder' => 'Skriv inn din melding her',
        'button' => [
            'messages' => 'Meldinger',
            'answer' => 'Svar',
            'hold' => 'Sett på vent',
            'minimize' => 'Minimaliser',
            'mute' => 'Mute',
            'unmute' => 'Unmute',
            'zoom_in' => 'Zoom inn',
            'zoom_out' => 'Zoom ut',
        ],
        'empty' => [
            'title' => 'Ledig plass for en videostrøm',
            'select' => 'Velg en bruker fra køen helt til høyre eller fra nedtrekksmenyen under',
            'placeholder' => 'Velg en bruker',
        ]
    ],
    'message' => [
        'stream' => 'Meldingsstrøm',
        'type' => 'Skriv inn din melding her'
    ],
    'police' => [
        'called' => [
            'no' => 'Politiet har ikke blitt tilkalt',
            'yes' => 'Politiet har blitt tilkalt',
            'asked' => 'Tilkall politiet!'
        ],
        'switch' => [
            'status' => 'Endre status på krisesenteret',
            'called' => 'Politiet er tilkalt',
            'not_called' => 'Politiet er ikke tilkalt'
        ],
        'status' => [
            'label' => 'Alarmstatus:',
            'on' => 'Alarmen er utløst',
            'off' => 'Alarmen er ikke utløst',
            'started' => 'Alarmen ble utløst:',
            'not_started' => 'Ikke utløst',
            'police_called' => 'Antall som har ringt politiet:',
            'users' => 'personer',
            'times' => 'Times'
        ]
    ],
    'trigger' => [
        'message' => 'Connecting to the shelter, please wait...(no)'
    ],
    'tac' => [
        'default' => '<b>Brukervilkår</b><br />
<br />
Ved å godta installasjon av denne app-en, godtar du at:<br />
<br />
<ul>
<li>appen får tilgang til ditt kamera og mikrofon, når du trykker på alarmknappen.</li>
<li>du får sms og/eller push-notifikasjon når skolens ledelse sender ut en krisemelding.</li>
<li>at systemet viser på en byggtegning ca hvor du befinner seg. Dette er ingen nøyaktig angivelse, men viser omtrentlig i hvilken del av en bygning du befinner deg.</li>
<li>at det gjøres opptak av all lyd og video som din telefon leverer til systemet når du utløser alarmen.</li>
</ul><br />
<br />
Når alarmen ikke er utløst, dvs. du ikke har trykket på alarmknappen, så ligger appen i bakgrunnen og samler ingen data om deg eller din telefon. Eneste grunn til at den ligger og kjører i bakgrunnen er at skolens ledelse skal kunne sende deg en melding i forbindelse med en alvorlig hendelse.
		'
    ]
];