<?php
return [
    'save' => 'Lagre',
    'tabs' => [
        'general' => 'Generelle innstillinger',
        'help' => 'Tiltakslister',
        'sms' => 'SMS (tekstmelding)',
        'phone' => 'Telefonisystem',
        'push' => 'Push-meldinger',
        'buttons' => 'Fysiske alarmgivere',
        'reports' => 'Rapporter',
        'logs' => 'Logs',
        'team' => 'Kriseledelsen',
        'reset' => 'Resett krisesenteret'
    ],
    'general' => [
        'title' => 'Generelle innstillinger',
        'description' => 'Her settes generelle innstillinger for din lokasjon. Det ligger inne default-innstillinger som er satt av super-admin. Er disse ok, så skal du ikke endre dem. I nedtrekksmenyen hvor du velger sortering av køen, benyttes begrepet "aktivitet". Med aktivitet menes all aktivitet i systemet; en ny alarm, en ny polititilkalling, en ny melding ellerv et anrop til krisesenteret. Det anbefales at køen er sortert etter aktivitet, med den nyeste aktiviteten nederst (slik det er i vanlige chatte-tjenester som FB-messenger, Skype, Viber m.fl)',
        'labels' => [
            'timezone' => 'Tidssone',
            'language' => 'Språk',
            'reconnect' => '*will be removed*',
            'ordering' => 'Default (standard) sortering av køen'
        ],
        'save' => 'Lagre'
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
        'title' => 'Fysiske alarmgivere',
        'description' => 'Her ser du om det er lagt inn fysiske alarmgiverne for din lokasjon. Du kan se om de er plassert riktig på kartet og ev. justere plasseringen. Skal det installeres en ny alarmgiver, må denne legges inn av super-admin.',
        'add' => 'Legg til alarmgiver',
        'table' => [
            'number' => 'Alarmgiver-nummer',
            'name' => 'Alaramgiver-navn',
            'cbf' => 'Plassering (kampus, bygning, etasje)',
            'mac' => 'KrisesenterPC-ens MAC-adresse',
            'ip' => 'KrisesenterPC-ens IP-adresse',
            'x' => 'X',
            'y' => 'Y',
            'options' => 'Opsjoner (se plassering på kart, endre plassering)'
        ],
        'placeholder' => 'Vennligst velg'

    ],
    'logs' => [
        'title' => 'Log',
        'description' => 'Beskrivelse',
        'table' => [
            'created_at' => 'Tidspunkt',
            'log_type' => 'Logtype',
            'device_type' => 'Enhedstype',
            'device_id' => 'EnhedsID',
            'fullname' => 'Fuldt navn',
            'mac_address' => 'MAC-adresse',
            'floor_id' => 'EtasjeID',
            'x' => 'X',
            'y' => 'Y',
            'log_types' => [
                'alarm_triggered' => 'Alarm utløst',
                'unknown_type' => 'Ukjent type'
            ]
        ]
    ],
    'maps' => [
        'title' => 'Kart/byggtegninger',
        'description' => 'Her legges kart/byggtegninger inn. En tegning pr bygning, pr. etasje. Er systemet integrert mot f.eks. Aruba Airwave, hentes kartene inn automatisk hver natt. Man kan når som helst manuelt synkronisere kartene ved å trykke på "Synkroniser manuelt"-knappen.',
        'sync' => 'Synkroniser manuelt'
    ],
    'push' => [
        'title' => 'Push-meldinger',
        'description' => 'Her oppretter du egne push-meldinger. Du bør først velge å importere systemets standardmeldinger. Kan de benyttes? Er det noen av dem som ikke kan benyttes, så skjuler du disse og ev. oppretter dine egne. ',
        'button' => [
            'new' => 'Opprett ny push-melding',
            'import' => 'Importer systemets standard-meldinger',
            'show' => 'Show(no)',
            'hide' => 'Skjul denne meldingen'
        ],
        'table' => [
            'label' => 'Navn på push-meldingen',
            'content' => 'Innhold i push-meldingen',
            'options' => 'Opsjoner (endre rekkefølgen, endre innhold, slette)',
        ]
    ],
    'reports' => [
        'title' => 'Rapporter',
        'description' => 'Aktivitetsrapporter',
        'no_results' => 'Ingen resultater funnet for de valgte kriteriene.',
        'filters' => [
            'date_range' => 'Datointervall',
            'false_alarm' => [
                'all' => 'Alle alarmer',
                'exclude_false' => 'Ekskluder falske alarmer',
                'only_false' => 'Vis bare falske alarmer'
            ]
        ],
        'table' => [
            'triggered_at' => 'Tidspunkt',
            'device_type' => 'Enhedstype',
            'device_id' => 'EnhedsID',
            'fullname' => 'Fuldt navn',
            'duration' => 'Varighed',
            'push_notifications' => 'Push-meldinger sendt',
            'video_chats' => 'Video-chatter',
            'download_log' => 'Hent log',
            'download_report' => 'Hent rapport',
            'download_csv' => 'Hent .csv',
            'download_pdf' => 'Hent .pdf',
            'has_video' => 'Video?',
            'video_tooltip' => 'Klik for at kopiere stien til videoen',
            'false_alarm' => "Falsk alarm?",
            'note' => 'Note',
            'yes' => 'Ja',
            'no' => 'Nei'
        ]
    ],
    'sms' => [
        'title' => 'SMS (tekstmeldinger)',
        'description' => 'Her oppretter du eller endrer på de to tekstmeldingene som går ut til skolens kriseledelse når alarmen utløses. Hvis standard-meldingen (som du ser nede til høyre ikke passer, så oppretter du nye meldinger.',
        'trigger' => 'Melding til skolens kriseledelse når alarmen utløses',
        'symbols' => 'Antall tegn du har igjen:',
        'default' => 'Systemets standardmelding:',
        'information' => 'Melding til skolens kriseledelse når alarmen utløses og alarmgiveren vil at politiet skal tilkalles :',
        'save' => 'Lagre',
    ],
    'team' => [
        'title' => 'Kriseledelsen',
        'description' => 'Her en oversikt over lokasjonens kriseledelse. Det er disse som vil bli varslet når alarm utløses. Varsling skjer via sms (vanlig tekstmelding), samt gjennom høyttalere på definerte telefoner. Medlemmene legges inn i en gruppe i Active Directory, og synkroniseres inn i systemet ved å klikke på "synkroniser medlemmer"-knappen.',
        'button' => [
            'sync' => 'Synkroniser medlemmer',
        ],
        'table' => [
            'name' => 'Navn',
            'email' => 'E-postadresse',
            'phone' => 'Mobiltelefonnummer'
        ]
    ],
];