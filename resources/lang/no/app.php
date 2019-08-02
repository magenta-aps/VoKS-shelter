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
        'default' => '		
		<b>Brukervilkår</b><br />
		<br />
		Ved installasjon av BComeSafe-appen, godtar du at appen får tilgang til ditt kamera, mikrofon og din posisjon når du er innenfor skolens område. Appen vil ikke fungere dersom du ikke er oppkoblet eller er utenfor skolens trådløse nettverk.<br />
		<br />
		Dersom du samtykker til det, vil appen også få tilgang til ditt mobilnummer.<br />
		<br />
		<b>Hva skjer når jeg trykker på alarmknappen?</b><br />
		- Appen åpner kamera og mikrofon for å sende lyd og video direkte til et sentralt system på skolen. Denne videostrømmen vil lagres i maksimum ett år. Skolens kriseledelse har tilgang til opptaket. Hvis nødvendig, kan skolens ledelse gi opptaket til politiet.<br />
		- Dersom du er på skolens område, vil appen sende informasjon til et sentralt system på skolen som viser omtrentlig hvor du befinner deg, Skolens kriseledelse har tilgang til din posisjon.<br />
		<br />
		<b></b>Hvilken funksjon har appen når alarmen ikke er utløst?</b><br />
		- Du får en push-notifikasjon i appen dersom skolens ledelse sender ut en melding i forbindelse med en alvorlig hendelse.<br />
		- Har du oppgitt ditt mobilnummer, vil du i tillegg få en tekstmelding (sms).<br />
		- Skolens ledelse kan ikke se din posisjon.
		'
    ]
];