<?php

return [
    'alarm' => [
        'title' => 'Konfigurering av talemeldingene som sendes ut når alarmen utløses',
        'desc' => 'Systemet er integrert med telefonsystemet. I telefonsystemet har du på forhånd laget en gruppe som det skal sendes talemelding til når alarmen utløses. Denne gruppen kan inneholde alle telefonene til medlemmene i kriseledelsen, supplert med telefoner i møterom, kopimaskinrom m.m.',
        'field' => [
            'group' => [
                'label' => 'Velg gruppen som skal få talemelding når alarmen utløses',
                'value' => 'Velg sone (gruppe)'
            ],
            'media' => [
                'label' => 'Velg meldingen som skal sendes',
                'value' => 'Velg melding'
            ],
            'interrupt' => [
                'label' => 'Velg meldingen som skal gis til samtalepartneren (når et medlem av kriseledelsen sitter opptatt i telefonen)',
                'value' => 'Ingen melding'
            ],
            'save' => [
                'label' => 'Lagre valgene'
            ]
        ]
    ],
    'broadcast' => [
        'title' => 'Direkte talemeldinger',
        'desc' => 'Under legger du inn et default internnummer som brukes for å sende ut en direkte talamelding (en “live”-melding). Dette vil normalt være internnummeret til den IP-telefonen som er plassert ved siden av krisesenterPC-en. Nummeret kan overstyres i selve krisesenteret.',
        'field' => [
            'number' => [
                'label' => 'Legg inn internnummer for den telefonen du vil bruke for å prate ut direkte talemeldinger'
            ],
            'save' => [
                'label' => 'Lagre telefonnummeret'
            ]
        ]
    ]
];
