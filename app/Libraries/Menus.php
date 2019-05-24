<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries;

class Menus
{
    protected $base = 'BComeSafe\Http\Controllers\\';
    protected $currentAction;

    protected $menu = [
        'main' => [
            'type' => 'controller',
            'items' => [
                [
                    'title' => 'system.menu.main.general',
                    'path' => 'system/general',
                    'controllers' => ['System\General\MainController']
                ],
                [
                    'title' => 'system.menu.main.school',
                    'path' => 'system/schools',
                    'controllers' => ['System\Schools\MainController']
                ],
                [
                    'title' => 'system.menu.main.buttons',
                    'path' => 'system/buttons',
                    'controllers' => ['System\Buttons\MainController']
                ],
                [
                    'title' => 'system.menu.main.map',
                    'path' => 'system/maps',
                    'controllers' => ['System\Maps\MainController']
                ]
            ]
        ],
        'system' => [
            'type' => 'action',
            'baseController' => 'System\General\MainController',
            'items' => [
                [
                    'title' => 'system.menu.general.system',
                    'path' => 'system/general',
                    'actions' => ['getIndex']
                ],
                [
                    'title' => 'system.menu.general.help',
                    'path' => 'system/general/help',
                    'actions' => ['getHelp']
                ],
                [
                    'title' => 'system.menu.general.push',
                    'path' => 'system/general/notifications',
                    'actions' => ['getNotifications']
                ],
                [
                    'title' => 'system.menu.general.sms',
                    'path' => 'system/general/sms',
                    'actions' => ['getSms']
                ]
            ]
        ],
        'admin' => [
            'type' => 'controller',
            'baseController' => 'System\Admin\MainController',
            'items' => [
                [
                    'title' => 'admin.tabs.general',
                    'path' => 'admin/general',
                    'controllers' => ['Admin\GeneralController']
                ],
                [
                    'title' => 'admin.tabs.phone',
                    'path' => 'admin/phone-system',
                    'controllers' => ['Admin\PhoneSystemController']
                ],
                [
                    'title' => 'admin.tabs.help',
                    'path' => 'admin/help',
                    'controllers' => ['Admin\HelpController']
                ],
                [
                    'title' => 'admin.tabs.sms',
                    'path' => 'admin/sms',
                    'controllers' => ['Admin\SmsController']
                ],
                [
                    'title' => 'admin.tabs.push',
                    'path' => 'admin/notifications',
                    'controllers' => ['Admin\NotificationController']
                ],
                [
                    'title' => 'admin.tabs.buttons',
                    'path' => 'admin/buttons',
                    'controllers' => ['Admin\ButtonController']
                ],
                [
                    'title' => 'admin.tabs.team',
                    'path' => 'admin/crisis-team',
                    'controllers' => ['Admin\CrisisTeamController']
                ],
                [
                    'title' => 'admin.tabs.reports',
                    'path' => 'admin/reports',
                    'controllers' => ['Admin\ReportsController']
                ],
                [
                    'title' => 'admin.tabs.logs',
                    'path' => 'admin/logs',
                    'controllers' => ['Admin\LogsController']
                ]
            ]
        ],
    ];

    public function __construct()
    {
        $this->currentAction = \Route::getCurrentRoute()->getAction()['uses'];
    }

    public function getMenu($type)
    {
        if (!isset($this->menu[$type])) {
            throw new \InvalidArgumentException($type . ' menu is not registered.');
        }

        $menu = $this->menu[$type];
        if ($menu['type'] === 'controller') {
            $menu['items'] = $this->getActiveControllerMenu($menu['items']);
        } elseif ($menu['type'] === 'action') {
            $menu['items'] = $this->getActiveActionMenu($menu['baseController'], $menu['items']);
        }

        return $menu['items'];
    }

    public function getActiveControllerMenu($items)
    {
        $count = count($items);

        for ($i = 0; $i < $count; $i++) {
            $items[$i]['current'] = $this->isControllerActive($items[$i]['controllers']);
        }

        return $items;
    }

    public function getActiveActionMenu($base, $items)
    {
        $count = count($items);

        for ($i = 0; $i < $count; $i++) {
            $items[$i]['current'] = $this->isActionActive($base, $items[$i]['actions']);
        }
        return $items;
    }

    public function isControllerActive($controllers)
    {
        for ($i = 0; $i < count($controllers); $i++) {
            if (str_contains($this->currentAction, $controllers[$i])) {
                return true;
            }
        }
        return false;
    }

    public function isActionActive($base, $actions)
    {
        for ($i = 0; $i < count($actions); $i++) {
            if (str_contains($this->currentAction, $base . '@' . $actions[$i])) {
                return true;
            }
        }
        return false;
    }
}
