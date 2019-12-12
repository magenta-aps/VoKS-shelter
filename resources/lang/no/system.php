<?php
return [
    'save' => 'Lagre',
    'cancel'    => 'Cancel',
    'remove'    => 'Remove',
    'edit'      => 'Edit',
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
            'push' => 'Tekstmeldinger til brukerne',
            'sms' => 'Tekstmelding (sms) til kriseledelsen'
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
        'sources' => [
	        'ad'		=> 'Active Directory',
	        'ale'		=> 'Aruba Ale',
          'aruba'   => 'Aruba',
	        'cisco'		=> 'Cisco CMX',
	        'google'	=> 'Google Maps',
        ],
        'push' => [
            'title' => 'Tekstmeldinger til brukerne (sms og push)',
            'description' => 'Her oppretter du eller endrer på de ferdiglagede meldingene (standard-meldingene)',
            'new' => 'Opprett ny melding',
            'table' => [
                'label' => 'Navn på meldingen',
                'content' => 'Innhold i melding',
                'options' => 'Opsjoner (endre, slette)',
            ]
        ],
        'sms' => [
            'title' => 'Tekstmelding (sms) til kriseledelsen',
            'description' => 'Her oppretter du eller endrer på de to tekstmeldingene som går ut til skolens kriseledelse når alarmen utløses.',
            'trigger' => 'Melding til skolens kriseledelse når alarmen utløses :',
            'information' => 'Melding til skolens kriseledelse når alarmen utløses og alarmgiveren vil at politiet skal tilkalles :',
            'symbols' => 'Antall tegn du har igjen til disposisjon:'
        ],
        'school' => [
            'title' => 'Konfigurer lokasjoner',
            'description' => 'Her legges lokasjonene inn. Er integrasjon satt opp mot Active Directory, vil lokasjonene hentes derfra. ',
            'sync' => 'Synkroniser lokasjoner',
            'add' => 'Legg til',
            'table' => [
                'name' => 'Lokasjonens navn',
                'mac' => 'KrisePC-ens MAC-adresse',
                'ip' => 'KrisePC-ens IP-adresse',
                'ad' => 'Active Directory ID',
                'phone' => 'Telefonsystem ID',
                'options' => 'Opsjoner',
                'url' => 'Url',
                'police_number' => 'Police number',
                'use_gps' => 'Use GPS',
                'display' => 'Display',
                'public' => 'Public',
                'controller' => 'Controller',
                'version' => 'Version'
            ]
        ],
        'maps' => [
            'title' => 'Kart/byggtegninger',
            'description' => 'Her legges kart/byggtegninger inn. En tegning pr bygning, pr. etasje. Er systemet integrert mot f.eks. Aruba Airwave, hentes kartene inn automatisk hver natt. Man kan når som helst manuelt synkronisere kartene ved å trykke på "Synkroniser manuelt"-knappen.',
            'sync' => 'Synkroniser manuelt'
        ],
        'defaults'  => [
	        'none'      => '-'
        ]
    ]
];