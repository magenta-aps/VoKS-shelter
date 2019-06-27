<?php
return [
    'title' => [
        'success' => 'Success',
        'error' => 'Error',
        'message_sent' => 'Message sent',
        'message_played' => 'Message played',
        'error_playing' => 'Error playing message',
        'warning' => 'Warning'
    ],
    'contents' => [
        'validation' => [
            'required' => 'Field is required.',
            'max_char' => 'Field cannot exceed 144 characters'
        ],
        'reset' => [
            'message' => 'Do you really want to reset Shelter?',
            'success' => 'Shelter has been reset. Reloading page.',
            'video' => [
                'prompt' => 'A video has been recorded. Please enter your name if you want to save the video. If you do not wish to save the video press "Cancel".',
                'success' => 'Video has been saved.',
                'error' => 'Video was not saved.',
            ]
        ],
        'school' => [
            'push' => [
                'select_client' => 'Please select at least one client.',
                'no_message' => 'Please type in a message or select a message template.',
                'too_long' => 'Message too long.',
                'sent' => 'Push notification has been sent.',
                'not_sent' => 'Push was not sent.',

            ]
        ],
        'system' => [
            'button' => [
                'save_success' => 'Button saved successfully.',
                'save_error' => 'Could not save button.',
                'remove_success' => 'Button removed successfully.',
                'remove_message' => 'Do you really want to delete this button?'
            ],
            'faq' => [
                'save_success' => 'Question saved successfully.',
                'save_error' => 'Could not save question.',
                'order_success' => 'Order saved successfully.',
                'remove_success' => 'Question removed successfully.',
                'remove_message' => 'Do you really want to delete this question?',
                'settings_saved' => 'Quick help settings have been saved.'

            ],
            'maps' => [
                'sync' => 'Syncing maps. Please wait.',
                'sync_success' => 'Maps have been synchronized. Reloading page.'
            ],
            'push' => [
                'save_success' => 'Notification template saved successfully.',
                'save_error' => 'Could not save notification template.',
                'remove_message' => 'Do you really want to delete this notification template?',
                'remove_success' => 'Notification template removed successfully.',
                'order_success' => 'Order saved successfully.'
            ],
            'school' => [
                'save_success' => 'School saved successfully.',
                'save_error' => 'Could not save school.',
                'sync' => 'Syncing schools. Please wait.',
                'sync_success' => 'Schools have been synchronized. Reloading page.',
                'remove_message' => 'Do you really want to delete this school?',
                'remove_success' => 'School removed successfully.'
            ]
        ],
        'admin' => [
              'button' => [
                  'save_success' => 'Button saved successfully.',
                  'save_error' => 'Could not save button.',
                  'remove_success' => 'Button removed successfully.'
              ],
            'team' => [
                'sync' => 'Synchronizing crisis center members. Please wait.',
                'complete' => 'Crisis center members have been synchronized. Reloading page.'
            ],
            'faq' => [
                'save_success' => 'Question saved successfully.',
                'save_error' => 'Could not save question.',
                'order_success' => 'Order saved successfully.',
                'remove_success' => 'Question removed successfully.',
                'remove_message' => 'Do you really want to delete this question?',
                'import_message' => 'Do you really want to import default questions?',
                'import_success' => 'Questions have been imported. Reloading page.',
                'settings_saved' => 'Quick help settings have been saved.'
            ],
            'maps' => [
                'sync' => 'Syncing maps. Please wait.',
                'sync_success' => 'Maps have been synchronized.'
            ],
            'push' => [
                'save_success' => 'Notification template saved successfully.',
                'save_error' => 'Could not save notification template.',
                'remove_message' => 'Do you really want to delete this notification template?',
                'import_message' => 'Do you really want to import default notification templates?',
                'import_success' => 'Push notification templates have been imported. Reloading page.',
                'remove_success' => 'Notification template removed successfully.',
                'order_success' => 'Order saved successfully.'
            ],
        ],
        'help' =>[
            'not_available' => 'Quick help is not available on this page.'
        ]
    ]
];
