<?php

$data = [
    'header' => [
        'menu' => [
            'main' => [
                'help' => 'Help documentation',
                'plan' => 'School plan',
                'stream' => 'Video streams'
            ],
            'users' => [
                'help' => 'Help',
                'settings' => 'Settings'
            ]
        ]
    ],
    'stream' => [
        'add' => 'Add',
        'chat_with' => 'Chat with:',
        'conversation' => 'Conversation',
        'minimize' => 'Close stream',
        'close_chat' => 'Close chat',
        'police_called' => '112',
        'mobile' => [
            'battery_loading' => 'Battery: Loading',
            'battery' => 'Battery: ',

        ],
        'placeholder' => 'Type your message',
        'button' => [
            'messages' => 'Messages',
            'answer' => 'Answer',
            'hold' => 'On Hold',
            'minimize' => 'Minimize',
            'mute' => 'Mute',
            'unmute' => 'Unmute',
            'zoom_in' => 'Zoom in',
            'zoom_out' => 'Zoom out',
        ],
        'empty' => [
            'title' => 'Empty place for video stream',
            'select' => 'Select stream from waiting line or list below',
            'placeholder' => 'Select stream',
        ]
    ],
    'message' => [
        'stream' => 'Message stream',
        'type' => 'Type your message'
    ],
    'police' => [
        'called' => [
            'no' => 'Police has not been called.',
            'yes' => 'Police has been called.',
            'asked' => 'Desktop App has asked to call the police'
        ],
        'switch' => [
            'status' => 'Change police status',
            'called' => 'Called',
            'not_called' => 'Not called'
        ],
        'status' => [
            'label' => 'Alarm status',
            'on' => 'ON',
            'off' => 'OFF',
            'started' => 'Started',
            'not_started' => 'Not started',
            'police_called' => 'Police called',
            'users' => 'users',
            'times' => 'times'
        ]
    ],
    'trigger' => [
        'message' => 'Connecting to the shelter, please wait.....'
    ],
    'tac' => [
        'default' => ""
    ]
];

$data['tac']['default'] = <<<ENDOFTAC
<b>Terms and Conditions</b>
<p>1: INTRODUCTION<br/>
Welcome to BComeSafe, an application for your phone that has been developed to set you in contact with immediate help in crisis situations. BComeSafe (hereafter the "App") is owned and run by BComeSafe ApS (hereafter "We", "Our", "Us" or BComeSafe).</p>
<p>2. ACCEPT OF TERMS AND CONDITIONS<br/>
These terms and conditions, along with other terms accepted in connection with use of functions in the App, regulate your use of BComeSafe. By using BComeSafe you accept, and are committed to, the terms and conditions. If you disagree to the terms and conditions, you will not be able to use the App. </p>
<p>We advise you to read the terms and conditions thoroughly before you use the App. BComeSafe maintains the right to at any time, based on our opinion, to change, modify, add or remove parts of the terms and conditions. In case these changes are impactful, we will notify you through a message in the App. By continued use of the App you accept and agree to such changes in the terms and conditions.</p>
<p>3: THE SERVICE<br/>
BComeSafe is a platform that connects the users of the App with a crisis team that will guide the users in crisis situations. The user triggers an alarm that notifies a dedicated crisis team about the situation, after which they can take the appropriate action.</p>
<p>4: PERSONAL INFORMATION<br/>
Please click <a href='https://bcomesafe.com/privacy-policy/'>here</a> to read our privacy policy. </p>
<p>We treat your personal information according to our privacy policy.</p>
<p>You guarantee that the information you deliver are correct, precise, valid and complete and that you have the right to share this information.</p>
<p>5: IMMATERIAL RIGHTS<br/>
BComeSafe and its licensors have, and will continue to have the sole right to the service and its content, functions and functionality and all related trademarks, copyright and immaterial rights. You can use such rights and material in relation to the use of BComeSafe according to these terms and conditions.</p>
<p>6: COMPLAINTS<br/>
If you notice bugs or errors in BComeSafe you can file a complaint. If you wish to file a complaint, you have to contact us without hesitation after the error has been discovered. </p>
<p>7: NO GUARANTEES<br/>
You use the App on own risk. BComeSafe is meant to provide extra security, but does in no way replace normal security measures that should be taken in any crisis situation and should always only be used as a complementary tool. The functionality on the phone is your responsibility alone. This includes, but is not limited to, keeping the phone and operating system up to date and free of malicious software, and to ensure that the phone is connected to a BComeSafe wi-fi. It is your responsibility alone to give BComeSafe the proper rights and access in your phone’s settings. BComeSafe does not guarantee that the App will work without interruptions and be accessible at certain times and/or places. BComeSafe does not guarantee that bugs or errors will be corrected or that the App is without virus or other harmful components.</p>
<p>8: DISCLAIMER<br/>
To the extent it is permitted according to the current law, BComeSafe will not be held responsible for injuries or personal damage connected to, or as a consequence of (i) use of, or inability to use the App; (ii) possible non-compliance of the service offered by the Service Provider or another third party; (iii) content in the App; or (iv) possible unauthorized access, use or change of your information. This includes, but is not limited to, delays, errors, performance issues, interruptions of the service, loss of data or other potential functionality issues in the App. </p>
<p>9: APPLICABLE LAW AND DISPUTE SOLUTION<br/>
These terms should be interpreted according to Norwegian law. Possible disputes will be subject to Norwegian courts exclusive jurisdiction.</p>
<p>The terms and conditions were last updated August 6th 2019.</p>
ENDOFTAC;

return $data;
