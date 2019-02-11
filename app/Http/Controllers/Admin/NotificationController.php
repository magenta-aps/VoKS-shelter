<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\PushNotificationMessage;
use BComeSafe\Models\PushNotificationMessageDefault;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function getIndex()
    {
        return view('admin.notifications.index');
    }

    public function getNotificationList()
    {
        return PushNotificationMessage::where('school_id', '=', \Shelter::getID())->orderBy('order', 'asc')->get();
    }

    public function postRemovePushNotification(Request $request)
    {
        $item = PushNotificationMessage::find($request->get('id'));
        if ($item) {
            $item->delete();
        }
        return $item;
    }

    public function postSavePushNotification(Request $request)
    {
        $data = $request->only(['id', 'label', 'content']);
        $data['school_id'] = \Shelter::getID();

        if ($data['id']) {
            $item = PushNotificationMessage::find($data['id']);
            $item->update($data);
        } else {
            $data['visible'] = 1;
            $item = PushNotificationMessage::create($data);
        }

        return $item;
    }

    public function postSaveVisibility(Request $request)
    {
        $data = $request->only(['id', 'visible']);
        $data['school_id'] = \Shelter::getID();
        if ($data['id']) {
            $item = PushNotificationMessage::find($data['id']);
            $item->update($data);
        }

        return $item;
    }

    public function postSaveNotificationOrder(Request $request)
    {
        $list = $request->all();

        foreach ($list as $position => $id) {
            if ($id) {
                PushNotificationMessage::find($id)->update(['order' => $position]);
            }
        }
    }

    public function getSync()
    {
        PushNotificationMessage::where('school_id', '=', \Shelter::getID())->delete();

        $defaults = PushNotificationMessageDefault::all();

        foreach ($defaults as $item) {
            $item = $item->toArray();
            $item['school_id'] = \Shelter::getID();

            PushNotificationMessage::create($item);
        }
    }
}
