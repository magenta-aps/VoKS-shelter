<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

/**
 * Norwegian translations for the quick help tour.
 * The order of the translations is the order of the tour.
 * Please keep Norwegian and English translation files in sync.
 *
 * Includes fake information in case there is no real users connected.
 *
 * Views:
 * - streams (home page)
 * - stream (large stream page)
 * - plan (school plan/map page)
 *
 * Structure:
 * - title: Title of the step
 * - content: Description
 * - element: Element name which is resolved to a selector
 */
return [
    'fake' => [
        'client' => [
            'name' => 'John Smith'
        ],
        'message' => [
            'content' => 'Lorem ipsum dolor sit amet.'
        ]
    ],
    'view' => [
        'streams' => [
            [
                'title' => 'Meldingskø',
	            'content' => 'Alle meldingene sendt fra brukere som har utløst alarmen kommer opp her. For å kommunisere med en bestemt brukere (chatte), skru lyden av eller på, eller se brukerens videostrøm; klikk på brukerens navn og klikk på ønsket knapp',
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Brukerens plassering',
                'content' => 'Her ser du hvor brukeren befinner seg. For å se plasseringen på et større kart, klikk på fanen "Alarmsted" i hovedmenyen øverst (fane nummer to).',
                'element' => 'STREAM_LOCATION_BUTTON'
            ],
            [
                'title' => 'Lyd av/på',
                'content' => 'Her velger du hvem du vil lytte på. Klikk for å skru lyden av eller på ',
                'element' => 'STREAM_VOLUME_BUTTON'
            ],
            [
                'title' => 'Meldinger',
                'content' => 'Her starer du en chat-konversasjon med denne brukeren. Hvis det er et lite tall som vises på knappen, er det antall uleste meldinger fra denne brukeren. Klikk på knappen for å lese de uleste meldingene. ',
                'element' => 'STREAM_MESSAGE_BUTTON'
            ],
            [
                'title' => 'Chattevindu',
                'content' => 'Chattevinduet lukkes ved å klikke på krysset i øvre høyre hjørnet av chattevinduet, eller på meldingsknappen under.',
                'element' => 'STREAM_CHAT_WINDOW'
            ],
            [
                'title' => 'Svar et innkommende anrop',
                'content' => 'Lyser denne knappen grønn, betyr det at en bruker ønsker å snakke med krisesenteret. Besvar anropet ved å klikke på knappen. Ved besvarelse skifter knappen farge til rød. Klikker du en gang til på knappen, setter du samtalen på vent. Det er kun brukeren som kan avslutte samtalen. Det betyr at du hele tiden ser hvem som kan snakke og ikke snakke.',
                'element' => 'STREAM_ANSWER_BUTTON'
            ],
            [

                'title' => 'Maksimer vinduet',
                'content' => 'Her maksimerer du videovinduet. Aktuelt å gjøre hvis du ønsker å se tydligere hva som skjer',
                'element' => 'STREAM_MAXIMIZE_BUTTON'
            ],
            [
                'title' => 'Ledig plass for videostrøm',
                'content' => 'Når det er ledig plass for en videostrøm, og det er brukere i køen helt til høyre, kan du velge å se vedkommendes videostrøm ved å klikke på brukerens "videokamera"-knapp, eller velge fra dropdown menyen her. ',
                'element' => 'EMPTY_STREAM_WINDOW'
            ],
            [
                'title' => 'Lite kart',
                'content' => 'Her vises brukerens plassering på kartet. Du kan se andre brukers plassering ved å klikke på "Posisjons"-knappen som er under hver videostrøm. Vil du se et større kart, med alle brukernes posisjoner, må du klikke på fanen "Alarmsted" i hovedmenyen øverst. .',
                'element' => 'SMALL_MAP',
                'placement' => 'top'
            ],
            [
                'title' => 'kø',
                'content' => 'Her er en oversikt over alle de andre brukerne som har utløst men som du ikke ser videostrømmene til. Listen (køen) er normalt sortert slik at den brukeren med sist utførte aktivitet, kommer nederst. For eksempel vises et lite tall i en orange firkant som indikasjon på det antall meldinger vedkommende har sendt. Hold muspekeren over brukerens navn, og klikk på ønsket menyvalg: lese/skrive meldinger til vedkommende (chatte), høre lyden fra vedkommende eller se videostrømmen til vedkommende.',
                'element' => 'WAITING_LINE',
                'placement' => 'left'
            ],
            [
                'title' => 'Sortering av kø',
                'content' => 'Her velger du hvordan sorteringen av køen skal være. I utgangspunktet sorters køen slik at den med sist aktivitet (f.eks. en bruker som nettopp har sendt en melding) kommer nederst. Dette kan du endre her. Når krisesenteret resettes, går innstillingen tilbake til den opprinnelige innstillingen. (Denne kan endres permanent under "Innstillinger" og "Generelle innstillinger").',
                'element' => 'WAITING_LINE_SORTING',
                'placement' => 'left'
            ],
            [
                'title' => 'Krisesenterets to modus',
                'content' => 'Krisesenteret har to modus; orange modus, hvor politiet ikke er tilkalt, og rød modus, hvor politiet er tilkalt. Når politiet er tilkalt vil du helt oppe i høyre hjørne av skjermen se hvor mange som har ringt politiet.',
                'element' => 'HEADER_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Change police status',
                'content' => 'Statusen til krisesenteret kan endres manuelt her. Dette må gjøres når en PC-bruker ber om at politiet må tilkalles. Det vil da midt i det orange feltet over stå "Tilkall politet!". Den som sitter med tastaturet må da manuelt endre statusen ved å skyve ovenstående skyvebryter til høyre, eller klikke på teksten "Politiet er tilkalt". Da endres krisesenterets modus til "rød", og det vil midt i feltet stå "Politiet har blitt tilkalt". Når en bruker utløser alarm fra alarm-app\'n og gjennom alarm-app\'n ringer politet, går krisesenteret automatisk i rød modus.',
                'element' => 'POLICE_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Status-felt',
                'content' => 'Her vise klokkelsettet når den første alarmen ble utløst, samt antall brukere som har ringt politiet fra sin smarttelefon (via alarm-app\'n).',
                'element' => 'ALARM_STATISTICS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Innstillinger',
                'content' => 'Et klikk her bringer deg til administrasjonssiden, hvor du kan gjøre en del instillinger. Disse instillingene gjøres kun for din lokasjon.',
                'element' => 'ADMINISTRATION_BUTTON',
                'placement' => 'left'
            ],
        ],
        'stream' => [
            [
                'title' => 'Meldingskø',
                'content' => 'Alle meldingene sendt fra brukere som har utløst alarmen kommer opp her. For å kommunisere med en bestemt brukere (chatte), skru lyden av eller på, eller se brukerens videostrøm; klikk på brukerens navn og klikk på ønsket knapp',
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],

            [
                'title' => 'Minimaliser vindu',
                'content' => 'Minimaliser videovinduet. Da kommer man samtidig tilbake til krisesenterets hovedbilde, hvor du ser alle valgte videostrømmer.',
                'element' => 'STREAM_MINIMIZE_BUTTON'
            ],
            [
                'title' => 'Svar et innkommende anrop',
                'content' => 'Lyser denne knappen grønn, betyr det at en bruker ønsker å snakke med krisesenteret. Besvar anropet ved å klikke på knappen. Ved besvarelse skifter knappen farge til rød. Klikker du en gang til på knappen, setter du samtalen på vent. Det er kun brukeren som kan avslutte samtalen. Det betyr at du hele tiden ser hvem som kan snakke og ikke snakke.',
                'element' => 'LARGE_STREAM_ANSWER_BUTTON'
            ],
            [
                'title' => 'Lyd av/på',
                'content' => 'Her velger du hvem du vil lytte på. Klikk for å skru lyden av eller på ',
                'element' => 'LARGE_STREAM_VOLUME_BUTTON'
            ],
            [
                'title' => 'Chat',
                'content' => 'Chat with single selected user',
                'element' => 'LARGE_STREAM_CHAT_WINDOW',
            ],
            [
                'title' => 'Map',
                'content' => 'Selected user location is shown on map. Crisis center can change the map by clicking the map icon on each video stream.',
                'element' => 'LARGE_STREAM_MAP',
            ],
            [
                'title' => 'Small video',
                'content' => 'Small video stream component has 7 video stream slots. Video streams are shown in the same order they were displayed on main shelter window.',
                'element' => 'SMALL_STREAM'
            ],
            [
                'title' => 'Empty small stream',
                'content' => 'When there are empty stream slots available, the crisis center member can choose which user video-audio stream he/she would like to see from a dropdown list ',
                'element' => 'SMALL_EMPTY_STREAM'
            ],
            [
                'title' => 'Waiting line',
                'content' => 'Crisis center can find all the users who triggered the alarm, but are not streaming video to video stream slots in the waiting line. Hover over selected user to start a chat, mute/unmute audio or view the video stream of the user.',
                'element' => 'WAITING_LINE',
                'placement' => 'left'
            ],
            [
                'title' => 'Sorting select',
                'content' => 'Select sorting type for users that are on the waiting line',
                'element' => 'WAITING_LINE_SORTING',
                'placement' => 'left'
            ],
            [
                'title' => 'Police Status',
                'content' => '“Police status bar” component, shows the status regarding contacting the police. The purpose of this component is to quickly inform the crisis center whether the police has been called.',
                'element' => 'HEADER_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Change police status',
                'content' => 'Police status can be changed manually by pressing "Called" or "Not called" on the status bar.',
                'element' => 'POLICE_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Alarm statistics',
                'content' => 'Information about alarm can be previewed on police status bar: time, when alarm has first been triggered and quantity of unique users who have called the police via mobile application (user count)',
                'element' => 'ALARM_STATISTICS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Administration button',
                'content' => 'Administration tools for the shelter page',
                'element' => 'ADMINISTRATION_BUTTON',
                'placement' => 'left'
            ],
        ],
        'plan' => [
            [
                'title' => 'Message feed',
                'content' => 'All messages sent form users who have triggered the alarm can be seen here. To start a chat, mute/unmute audio or view video stream, press on name of the user in the message',
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Tools',
                'content' => 'Tools for map navigation and selecting users to message sending component',
                'element' => 'SCHOOL_PLAN_MAP_TOOLS'
            ],
            [
                'title' => 'Push notification',
                'content' => 'Push notifications to selected users are sent via this component',
                'element' => 'PUSH_NOTIFICATION_TAB',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Audio message',
                'content' => 'Prerecorded or broadcasted ad hoc audio messages are selected in audio component',
                'element' => 'AUDIO_MESSAGE_TAB',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Notification history',
                'content' => 'A crisis center member can see additional historical/statistical information in "History" tab',
                'element' => 'MESSAGE_HISTORY_TAB'
            ],
            [
                'title' => 'Small video',
                'content' => 'Small video stream component has 7 video stream slots. Video streams are shown in the same order they were displayed on main shelter window.',
                'element' => 'SMALL_STREAM'
            ],
            [
                'title' => 'Empty small stream',
                'content' => 'When there are empty stream slots available, the crisis center member can choose which user video-audio stream he/she would like to see from a dropdown list ',
                'element' => 'SMALL_EMPTY_STREAM'
            ],
            [
                'title' => 'Waiting line',
                'content' => 'Crisis center can find all the users who triggered the alarm, but are not streaming video to video stream slots in the waiting line. Hover over selected user to start a chat, mute/unmute audio or view the video stream of the user.',
                'element' => 'WAITING_LINE',
                'placement' => 'left'
            ],
            [
                'title' => 'Sorting select',
                'content' => 'Select sorting type for users that are on the waiting line',
                'element' => 'WAITING_LINE_SORTING',
                'placement' => 'left'
            ],
            [
                'title' => 'Police Status',
                'content' => '“Police status bar” component, shows the status regarding contacting the police. The purpose of this component is to quickly inform the crisis center whether the police has been called.',
                'element' => 'HEADER_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Change police status',
                'content' => 'Police status can be changed manually by pressing "Called" or "Not called" on the status bar.',
                'element' => 'POLICE_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Alarm statistics',
                'content' => 'Information about alarm can be previewed on police status bar: time, when alarm has first been triggered and quantity of unique users who have called the police via mobile application (user count)',
                'element' => 'ALARM_STATISTICS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Administration button',
                'content' => 'Administration tools for the shelter page',
                'element' => 'ADMINISTRATION_BUTTON',
                'placement' => 'left'
            ],
        ]
    ]
];