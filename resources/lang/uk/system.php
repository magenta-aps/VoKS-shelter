<?php
return [
    'save' => 'Зберегти',
    'cancel'    => 'Відмінити',
    'remove'    => 'Видалити',
    'edit'      => 'Додати',
    'menu' => [
        'main' => [
            'general' => 'Загальні налаштування',
            'school' => 'Управління кризовими центрами',
            'buttons' => 'IP Кнопки',
            'map' => 'Мапа',
        ],
        'general' => [
            'system' => 'Система',
            'help' => 'Допомога за замовчуванням',
            'push' => 'Push-сповіщення за замовчуванням',
            'sms' => 'Текст SMS за замовчуванням'
        ],
    ],
    'contents' => [
        'system' => [
            'title' => 'Система',
            'description' => 'Налаштування системи за замовчуванням',
            'timezone' => 'Часовий пояс за замовчуванням',
            'language' => 'мова за замовчуванням',
            'reconnect' => 'Час відновлення аудіо за замовчуванням',
            'ordering' => 'Порядок за замовчуванням при черзі',
            'sms' => 'SMS провайдер',
            'phone' => 'Провайдер телефонної системи',
            'user' => 'Джерело даних користувача',
            'device' => 'Джерело місцезнаходження пристрою'
        ],
		'sources' => [
			'ad'		=> 'Active Directory',
			'ale'		=> 'Aruba Ale',
      'aruba'		=> 'Aruba',
			'cisco'		=> 'Cisco CMX',
			'google'	=> 'Google Maps',
		],
    'push' => [
        'title' => 'Push-сповіщення',
        'description' => 'Встановіть налаштування за замовчуванням для Push-сповіщень',
        'new' => 'Нове сповіщення за замовчуванням',
        'table' => [
            'label' => 'Мітка сповіщень',
            'content' => 'Зміст сповіщень',
            'options' => 'Параметри',
        ]
    ],
    'sms' => [
        'title' => 'SMS',
        'description' => 'Налаштуйте стандартні SMS при спрацюванні сигналу тривоги',
        'trigger' => 'Повідомлення про початковий сигнал тривоги',
        'information' => 'Інформаційне повідомлення кризового центру',
        'symbols' => 'Символів залишилось:'
    ],
    'school' => [
        'title' => 'Налаштування шкіл',
        'description' => 'Управляти налаштуваннями школи',
        'sync' => 'Синхронізувати школи',
        'add' => 'Додати нову',
        'table' => [
            'name' => 'Назва школи',
            'mac' => 'Shelter PC MAC',
            'ip' => 'Shelter PC IP',
            'ad' => 'Active Directory ID',
            'phone' => 'ID системи телефону',
            'options' => 'Параметри',
            'url' => 'Url',
            'police_number' => 'Номер поліції',
            'use_gps' => 'Користуйтесь GPS',
            'display' => 'Дисплей',
            'public' => 'Публічний',
            'controller' => 'Контролер'
        ]
    ],
    'maps' => [
        'title' => 'Мапи',
        'description' => 'Синхронізувати та переглянути карти',
        'sync' => 'Синхронізувати мапи'
    ],
    'defaults'  => [
      'none'      => 'Жоден'
    ]
  ]
];