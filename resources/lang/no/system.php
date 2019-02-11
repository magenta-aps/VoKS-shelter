<?php
return [
    'save' => 'Lagre',
    'menu' => [
        'main' => [
            'general' => 'Generelle innstillinger',
            'school' => 'Konfigurer lokasjoner',
            'buttons' => 'Fysiske alarmgivere',
            'map' => 'Kart/byggtegninger',
        ],
        'general' => [
            'system' => 'Systemsettinger',
            'help' => 'Tiltakslister',
            'push' => 'Push-meldinger',
            'sms' => 'SMS (tekstmelding)'
        ],
    ],
    'contents' => [
        'system' => [
            'title' => 'Systemsettinger',
            'description' => 'Her settes en del overordnete parametre, som vil gjelde for hele systemet (alle lokasjonene)',
            'timezone' => 'Default tidssone',
            'language' => 'Default språk',
            'reconnect' => '*Will be removed*',
            'ordering' => 'Default rekkefølge i kø',
            'sms' => 'SMS leverandør',
            'phone' => 'Telefonisystem',
            'user' => 'Kilde for brukere',
            'device' => 'Kilde for enheter'
        ],
        'push' => [
            'title' => 'Push-meldinger',
            'description' => 'Her oppretter du eller endrer på de ferdiglagede push-meldingene (standard-meldingene)',
            'new' => 'Opprett ny push-melding',
            'table' => [
                'label' => 'Navn på push-meldingen',
                'content' => 'Innhold i push-melding',
                'options' => 'Opsjoner (endre, slette)',
            ]
        ],
        'sms' => [
            'title' => 'SMS (tekstmelding)',
            'description' => 'Her oppretter du eller endrer på de to tekstmeldingene som går ut til skolens kriseledelse når alarmen utløses.',
            'trigger' => 'Melding til skolens kriseledelse når alarmen utløses :',
            'information' => 'Melding til skolens kriseledelse når alarmen utløses og alarmgiveren vil at politiet skal tilkalles :',
            'symbols' => 'Antall tegn du har igjen til disposisjon:'
        ],
        'school' => [
            'title' => 'Konfigurer lokasjoner',
            'description' => 'Her legges lokasjonene inn. Er integrasjon satt opp mot Active Directory, vil lokasjonene hentes derfra. ',
            'sync' => 'Synkroniser lokasjoner',
            'table' => [
                'name' => 'Lokasjonens navn',
                'mac' => 'KrisePC-ens MAC-adresse',
                'ip' => 'KrisePC-ens IP-adresse',
                'ad' => 'Active Directory ID',
                'phone' => 'Telefonsystem ID',
                'options' => 'Opsjoner'
            ]
        ],
        'maps' => [
            'title' => 'Kart/byggtegninger',
            'description' => 'Her legges kart/byggtegninger inn. En tegning pr bygning, pr. etasje. Er systemet integrert mot f.eks. Aruba Airwave, hentes kartene inn automatisk hver natt. Man kan når som helst manuelt synkronisere kartene ved å trykke på "Synkroniser manuelt"-knappen.',
            'sync' => 'Synkroniser manuelt'
        ]
    ]
];