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
            'name' => 'Іван Коваленко'
        ],
        'message' => [
            'content' => 'Lorem ipsum dolor sit amet.'
        ]
    ],
    'view' => [
        'streams' => [
            [
                'title' => 'Потік повідомлень',
                'content' => "Усі повідомлення, надіслані користувачами, що надіслали сигнал тривоги, можна побачити тут. Щоб почати чат, вимкнути / увімкнути звук або переглянути відеопотік, натисніть на ім'я користувача в повідомленні.",
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Місцезнаходження',
                'content' => "Перевірте позицію користувача на мапі. Місцезнаходження користувача буде показано на малій мапі користувача (8-й контейнер).",
                'element' => 'STREAM_LOCATION_BUTTON'
            ],
            [
                'title' => 'Гучність',
                'content' => 'Вимкнути / Увімкнути звук від користувача до shelter',
                'element' => 'STREAM_VOLUME_BUTTON'
            ],
            [
                'title' => 'Повідомлення',
                'content' => 'Відкрити чат із цим користувачем. Кількість непрочитаних повідомлень від користувача відображається на кнопці.',
                'element' => 'STREAM_MESSAGE_BUTTON'
            ],
            [
                'title' => 'Вікно чату',
                'content' => 'Чат можна закрити, натиснувши хрестик у верхньому правому куті, або знову натиснувши на ту ж кнопку чату',
                'element' => 'STREAM_CHAT_WINDOW'
            ],
            [
                'title' => 'Відповідь',
                'content' => "Відповідь на дзвінок користувача та включення двостороннього аудіозв'язку. Кнопка відповіді має три стани: Вимкнено - користувач не викликає кризовий центр; Зелений - Користувач викликає кризовий центр; Червоний - Розмова триває.",
                'element' => 'STREAM_ANSWER_BUTTON'
            ],
            [
                'title' => 'Збільшити до максимуму',
                'content' => 'Збільшити вікно відео',
                'element' => 'STREAM_MAXIMIZE_BUTTON'
            ],
            [
                'title' => 'Вікно потоку пусте',
                'content' => 'Якщо активних потоків не вибрано, учасник кризового центру може вибрати, відео-аудіопотік від якого користувач він / вона хотіли б бачити з випадаючого списку.',
                'element' => 'EMPTY_STREAM_WINDOW'
            ],
            [
                'title' => 'Мала мапа',
                'content' => 'На останньому компоненті (8-му) головного вікyf Shelter -у можна побачити мапу користувача. Кризовий центр може змінювати мапу, натискаючи на значок мапи на кожному відеопотоці.',
                'element' => 'SMALL_MAP',
                'placement' => 'top'
            ],
            [
                'title' => 'Черга',
                'content' => 'Кризовий центр може знайти всіх користувачів, які натиснули сигнал тривоги, але не передають відео на слоти відеопотоку в черзі. Наведіть курсор на вибраного користувача, щоб розпочати чат, вимкнути / увімкнути звук або переглянути відеопотік користувача.',
                'element' => 'WAITING_LINE',
                'placement' => 'left'
            ],
            [
                'title' => 'Вибір сортування',
                'content' => 'Виберіть тип сортування для користувачів в черзі',
                'element' => 'WAITING_LINE_SORTING',
                'placement' => 'left'
            ],
            [
                'title' => 'Статус поліції',
                'content' => 'Компонент «Статус поліції» показує статус контакту з поліцією. Метою цього компоненту є швидке інформування кризового центру про те, чи викликали поліцію.',
                'element' => 'HEADER_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Змінити статус поліції',
                'content' => 'Статус поліції можна змінювати вручну, натиснувши "Викликано" або "Не викликано" на панелі статусу.',
                'element' => 'POLICE_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Статистика сигналів тривоги',
                'content' => 'Відомості про сигали тривоги можна переглянути на панелі статусу поліції: час, коли сигнал спрацював вперше, і кількість унікальних користувачів, які викликали поліцію через мобільний додаток (кількість користувачів).',
                'element' => 'ALARM_STATISTICS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Кнопка адміністрування',
                'content' => 'Інструменти адміністрування для сторінки shelter',
                'element' => 'ADMINISTRATION_BUTTON',
                'placement' => 'left'
            ],
        ],
        'stream' => [
            [
                'title' => 'Потік повідомлень',
                'content' => "Усі повідомлення, надіслані користувачами, що надіслали сигнал тривоги, можна побачити тут. Щоб почати чат, вимкнути / увімкнути звук або переглянути відеопотік, натисніть на ім'я користувача в повідомленні.",
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Згорнути',
                'content' => 'Мінімізувати велике вікно відео до головного екрану Shelter',
                'element' => 'STREAM_MINIMIZE_BUTTON'
            ],
            [
                'title' => 'Відповідь',
                'content' => "Відповідь на дзвінок користувача та включення двостороннього аудіозв'язку. Кнопка відповіді має три стани: Вимкнено - користувач не викликає кризовий центр; Зелений - користувач викликає кризовий центр; Червоний - розмова триває.",
                'element' => 'LARGE_STREAM_ANSWER_BUTTON'
            ],
            [
                'title' => 'Контроль гучності',
                'content' => 'Вимкнути / Увімкнути звук від користувача до Shelter',
                'element' => 'LARGE_STREAM_VOLUME_BUTTON'
            ],
            [
                'title' => 'Чат',
                'content' => 'Чат з одним вибраним користувачем',
                'element' => 'LARGE_STREAM_CHAT_WINDOW',
            ],
            [
                'title' => 'Мапа',
                'content' => 'Розташування вибраного користувача показано на мапі. Кризовий центр може змінити мапу, натиснувши на значок мапи на кожному відеопотоці.',
                'element' => 'LARGE_STREAM_MAP',
            ],
            [
                'title' => 'Мале відео',
                'content' => 'Компонент малого відеопотоку має 7 слотів відеопотоку. Відеопотоки відображаються в тому ж порядку, в якому вони відображалися на головному вікні Shelter',
                'element' => 'SMALL_STREAM'
            ],
            [
                'title' => 'Малий потік порожній',
                'content' => 'Якщо є вільні порожні слоти потоку, співробітник кризового центру може вибрати, якого користувача відео-аудіопотоку він/вона хотіли б бачити з  випадаючого списку.',
                'element' => 'SMALL_EMPTY_STREAM'
            ],
            [
                'title' => 'Черга',
                'content' => 'Кризовий центр може знайти всіх користувачів, які натиснули сигнал тривоги, але не передають відео на слоти відеопотоку в черзі. Наведіть курсор на вибраного користувача, щоб розпочати чат, вимкнути / увімкнути звук або переглянути відеопотік користувача.',
                'element' => 'WAITING_LINE',
                'placement' => 'left'
            ],
            [
                'title' => 'Вибір сортування',
                'content' => 'Виберіть тип сортування для користувачів в черзі',
                'element' => 'WAITING_LINE_SORTING',
                'placement' => 'left'
            ],
            [
                'title' => 'Статус поліції',
                'content' => 'Компонент «Статус поліції» показує статус контакту з поліцією. Метою цього компоненту є швидке інформування кризового центру про те, чи викликали поліцію.',
                'element' => 'HEADER_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Змінити статус поліції',
                'content' => 'Статус поліції можна змінювати вручну, натиснувши "Викликано" або "Не викликано" на панелі статусу.',
                'element' => 'POLICE_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Статистика сигналів тривоги',
                'content' => 'Відомості про сигали тривоги можна переглянути на панелі статусу поліції: час, коли сигнал спрацював вперше, і кількість унікальних користувачів, які викликали поліцію через мобільний додаток (кількість користувачів).',
                'element' => 'ALARM_STATISTICS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Кнопка адміністрування',
                'content' => 'Інструменти адміністрування для сторінки shelter',
                'element' => 'ADMINISTRATION_BUTTON',
                'placement' => 'left'
            ],
        ],
        'plan' => [
            [
                'title' => 'Потік повідомлень',
                'content' => "Усі повідомлення, надіслані користувачами, що надіслали сигнал тривоги, можна побачити тут. Щоб почати чат, вимкнути / увімкнути звук або переглянути відеопотік, натисніть на ім'я користувача в повідомленні.",
                'element' => 'MESSAGE_FEED_MESSAGE'
            ],
            [
                'title' => 'Інструменти',
                'content' => 'Інструменти для мапи навігації та вибору користувачів до компоненту надсилання повідомлення',
                'element' => 'SCHOOL_PLAN_MAP_TOOLS'
            ],
            [
                'title' => 'Push-сповіщення',
                'content' => 'Push-сповіщення для вибраних користувачів надіслані через цей компонент',
                'element' => 'PUSH_NOTIFICATION_TAB',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Звукове повідомлення',
                'content' => 'Попередньо записані чи передані (трансльовані) ad hoc звукові повідомлення є вибрані у звуковому компоненті.',
                'element' => 'AUDIO_MESSAGE_TAB',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Історія сповіщень',
                'content' => 'Член кризового центру може переглядати додаткову історію/ статистичну інформацію на вкладці "Історія"',
                'element' => 'MESSAGE_HISTORY_TAB'
            ],
            [
                'title' => 'Мале відео',
                'content' => 'Компонент малого відеопотоку має 7 слотів відеопотоку. Відеопотоки відображаються в тому ж порядку, в якому вони відображалися на головному вікні Shelter',
                'element' => 'SMALL_STREAM'
            ],
            [
                'title' => 'Малий потік порожній',
                'content' => 'Якщо є вільні порожні слоти потоку, співробітник кризового центру може вибрати, якого користувача відео-аудіопотоку він/вона хотіли б бачити з  випадаючого списку.',
                'element' => 'SMALL_EMPTY_STREAM'
            ],
            [
                'title' => 'Черга',
                'content' => 'Кризовий центр може знайти всіх користувачів, які натиснули сигнал тривоги, але не передають відео на слоти відеопотоку в черзі. Наведіть курсор на вибраного користувача, щоб розпочати чат, вимкнути / увімкнути звук або переглянути відеопотік користувача.',
                'element' => 'WAITING_LINE',
                'placement' => 'left'
            ],
            [
                'title' => 'Вибір сортування',
                'content' => 'Виберіть тип сортування для користувачів в черзі.',
                'element' => 'WAITING_LINE_SORTING',
                'placement' => 'left'
            ],
            [
                'title' => 'Статус поліції',
                'content' => 'Компонент «Статус поліції» показує статус контакту з поліцією. Метою цього компоненту є швидке інформування кризового центру про те, чи викликали поліцію.',
                'element' => 'HEADER_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Змінити статус поліції',
                'content' => 'Статус поліції можна змінювати вручну, натиснувши "Викликано" або "Не викликано" на панелі статусу.',
                'element' => 'POLICE_STATUS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Статистика сигналів тривоги',
                'content' => 'Відомості про сигали тривоги можна переглянути на панелі статусу поліції: час, коли сигнал спрацював вперше, і кількість унікальних користувачів, які викликали поліцію через мобільний додаток (кількість користувачів).',
                'element' => 'ALARM_STATISTICS',
                'placement' => 'bottom'
            ],
            [
                'title' => 'Кнопка адміністрування',
                'content' => 'Інструменти адміністрування для сторінки shelter',
                'element' => 'ADMINISTRATION_BUTTON',
                'placement' => 'left'
            ],
        ]
    ]
];