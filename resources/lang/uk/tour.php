<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

/**
 * English translations for the quick help tour.
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
                'title' => 'Message feed',
                'content' => 'All messages sent form users who have triggered the alarm can be seen here. To start a chat, mute/unmute audio or view video stream, press on name of the user in the message',
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Location',
                'content' => 'Check the user’s position on the map. User location will be shown on small user map (8th container)',
                'element' => 'STREAM_LOCATION_BUTTON'
            ],
            [
                'title' => 'Volume ',
                'content' => 'Mute/Unmute audio from user to shelter',
                'element' => 'STREAM_VOLUME_BUTTON'
            ],
            [
                'title' => 'Messages',
                'content' => 'Open chat with this user. Number of unread messages from user is shown on the button. ',
                'element' => 'STREAM_MESSAGE_BUTTON'
            ],
            [
                'title' => 'Chat window',
                'content' => 'Chat is closed by pressing a cross in the top right corner or by pressing the same chat button again',
                'element' => 'STREAM_CHAT_WINDOW'
            ],
            [
                'title' => 'Answer',
                'content' => 'Answer to a user call and enable a two way audio connection. Answer button has three states: Disabled - The user is not calling crisis center; Green - User is calling the crisis center; Red - Conversation is in progress. ',
                'element' => 'STREAM_ANSWER_BUTTON'
            ],
            [
                'title' => 'Maximize',
                'content' => 'Enlarge video window',
                'element' => 'STREAM_MAXIMIZE_BUTTON'
            ],
            [
                'title' => 'Empty stream window',
                'content' => 'When there are no active streams selected the crisis center member can choose which user video-audio stream he/she would like to see from a dropdown list ',
                'element' => 'EMPTY_STREAM_WINDOW'
            ],
            [
                'title' => 'Small map',
                'content' => 'On the last component (8th) of the main Shelter window map of the user can be seen. Crisis center can change the map by clicking the map icon on each video stream.',
                'element' => 'SMALL_MAP',
                'placement' => 'top'
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
        'stream' => [
            [
                'title' => 'Message feed',
                'content' => 'All messages sent form users who have triggered the alarm can be seen here. To start a chat, mute/unmute audio or view video stream, press on name of the user in the message',
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Minimize',
                'content' => 'Minimize large video window to main shelter screen',
                'element' => 'STREAM_MINIMIZE_BUTTON'
            ],
            [
                'title' => 'Answer',
                'content' => 'Answer to a user call and enable a two way audio connection. Answer button has three states: Disabled - The user is not calling crisis center; Green - User is calling the crisis center; Red - Conversation is in progress. ',
                'element' => 'LARGE_STREAM_ANSWER_BUTTON'
            ],
            [
                'title' => 'Volume control',
                'content' => 'Mute/Unmute audio from user to shelter',
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