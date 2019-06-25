<?php
return [
    'title' => [
        'success' => 'Success(no)',
        'error' => 'Feil',
        'message_sent' => 'Melding sent',
        'message_played' => 'Melding spilt av',
        'error_playing' => 'Feil ved avspilling av melding',
        'warning' => 'Advarsel'
    ],
    'contents' => [
        'validation' => [
            'required' => 'Feltet er påkrevet.',
            'max_char' => 'Feltet kan ikke inneholde fler enn 144 tegn.'
        ],
        'reset' => [
            'message' => 'Vil du virkelig resette krisesenteret?',
            'success' => 'Krisesenteret er resatt, siden oppdateres.',
        ],
        'school' => [
            'push' => [
                'select_client' => 'Velg minst en klient',
                'no_message' => 'Skriv inn en melding eller velg en av de predefinerte meldingene',
                'too_long' => 'Meldingen er for lang.',
                'sent' => 'Push-meldingen har blitt sendt.',
                'not_sent' => 'Push-meldingen har ikke blitt sendt.',

            ]
        ],
        'system' => [
            'button' => [
                'save_success' => 'Knappen ble lagret.',
                'save_error' => 'Kunne ikke lagre knappen.',
                'remove_success' => 'Knappen er fjernet.',
                'remove_message' => 'Vil du virkelig fjerne denne knappen?'
            ],
            'faq' => [
                'save_success' => 'Tiltakslista er lagret.',
                'save_error' => 'Kunne ikke lagre tiltakslista.',
                'order_success' => 'Rekkefølgen er lagret.',
                'remove_success' => 'Tiltakslista er slettet.',
                'remove_message' => 'Vil du virkelig slette denne tiltakslista?',
                'settings_saved' => 'Tiltakslistene har blitt lagret'

            ],
            'maps' => [
                'sync' => 'Synkroniserer kartene, vennligst vent.',
                'sync_success' => 'Kartene har blitt synkronisert. Vennligst oppdater siden.'
            ],
            'push' => [
                'save_success' => 'Den predefinerte meldingen ble lagret',
                'save_error' => 'Kunne ikke lagre den predefinerte meldingen',
                'remove_message' => 'Vil du virkelig slette denne predefinerte meldingen?',
                'remove_success' => 'Den predefinerte meldingen er slettet.',
                'order_success' => 'Rekkefølgen er lagret.'
            ],
            'school' => [
                'save_success' => 'Lokasjon lagret.',
                'save_error' => 'Kan ikke lagre lokasjonen.',
                'sync' => 'Synkroniser lokasjonene, vennligst vent.',
                'sync_success' => 'Lokasjonene har blitt synkronisert, vennligst oppdater siden.',
                'remove_message' => 'Vil du virkelig slette denne lokasjonen?',
                'remove_success' => 'Lokasjonen er slettet.'
            ]
        ],
        'admin' => [
              'button' => [
                  'save_success' => 'Knappen er lagret.',
                  'save_error' => 'Kunne ikke lagre knappen.',
                  'remove_success' => 'Knappet er slettet.'
              ],
            'team' => [
                'sync' => 'Synkroniser medlemmene til kriseledelsen',
                'complete' => 'Medlemmene til kriseledelsen har blitt synkronisert, vennligst oppdater siden.'
            ],
            'faq' => [
                'save_success' => 'Tiltakslista er lagret',
                'save_error' => 'Kunne ikke lagre tiltakslista.',
                'order_success' => 'Rekkefølgen er lagret.',
                'remove_success' => 'Tiltakslista er slettet.',
                'remove_message' => 'Vil du virkelig slette denne tiltakslista?',
                'import_message' => 'Vil du virkelig importere standard tiltakslistene?',
                'import_success' => 'Tiltakslistene er importert, vennligst oppdater siden.',
                'settings_saved' => 'Tiltakslistene har blitt oppdatert.'
            ],
            'maps' => [
                'sync' => 'Synkroniserer kartene, vennligst vent.',
                'sync_success' => 'Kartene har blitt synkronisert.'
            ],
            'push' => [
                'save_success' => 'Den predefinerte push-meldingen er lagret.',
                'save_error' => 'Kunne ikke lagre den predefinerte push-meldingen',
                'remove_message' => 'Vil du virkelig slette denne predefinerte push-meldingen?',
                'import_message' => 'Vil du virkelig importere de standard predefinerte push-meldingene?',
                'import_success' => 'De standard predefinerte push-meldingene er importert, siden oppdateres.',
                'remove_success' => 'De predefinerte meldingene er slettet',
                'order_success' => 'Rekkefølgen er lagret'
            ],
            'reports' => [
                'save_success' => 'Rapporten er lagret.',
                'save_error' => 'Kunne ikke lagre rapporten',
                'remove_message' => 'Vil du virkelig slette denne rapporten?',
                'remove_success' => 'Rapporten er slettet'
            ]
        ],
        'help' =>[
            'not_available' => 'Hjelpefunksjonen er ikke tilgjengelig for denne siden'
        ]
    ]
];