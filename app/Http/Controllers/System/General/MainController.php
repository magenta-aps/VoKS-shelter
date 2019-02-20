<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\General;

use BComeSafe\Http\Requests;
use BComeSafe\Models\FaqDefault;
use BComeSafe\Models\HelpFileDefault;
use BComeSafe\Models\PushNotificationMessageDefault;
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SmsMessageDefault;
use Illuminate\Http\Request;

/**
 * Class MainController
 *
 * @package BComeSafe\Http\Controllers\System\General
 */
class MainController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $defaults = SchoolDefault::first();
        if (!$defaults) {
            $defaults = SchoolDefault::firstOrNew(
                [
                    'ordering' => config('sorting.default'),
                    'timezone' => 'Europe/Vilnius',
                    'locale' => 'en'
                ]
            );
        }

        return view(
            'system.general.index',
            [
                'defaults' => $defaults,
                'timezones' => get_timezone_list(),
                'languages' => get_available_languages(),
                'orderingOptions' => get_sorting_options(),
                'smsProviders' => prepend_none_option( \Component::get('Sms')->getIntegrations() ),
                'phoneSystemProviders' => prepend_none_option( \Component::get('PhoneSystem')->getIntegrations() ),
                'userDataSources' => get_available_user_data_sources(),
                'clientDataSources' => get_available_location_sources(),
            ]
        );
    }

    /**
     * @param Requests\SaveSchoolDefaultsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSave(Requests\SaveSchoolDefaultsRequest $request)
    {
        $data = collect( $request->only(
            [
                'timezone',
                'locale',
                'ordering',
                'sms_provider',
                'phone_system_provider',
                'user_data_source',
                'client_data_source'
            ]
        ) );

	    $data->transform( function( $value )
	    {
	    	return !empty($value) ? $value : null;
	    } );

        $school = SchoolDefault::first();

        if (!$school) {
            $school = SchoolDefault::firstOrNew($data->all());
        } else {
            $school->update($data->all());
        }

        $school->save();

        return back();
    }

    /**
     * FAQ
     */
    public function getHelp()
    {
        $file = HelpFileDefault::first();

        if (!$file) {
            $file = HelpFileDefault::firstOrNew(
                [
                    'name' => '',
                    'file_url' => '',
                    'file_path' => '',
                    'description' => '',
                    'police_file_url' => '',
                    'police_file_path' => '',
                    'police_name' => '',
                ]
            );
        }

        return view(
            'system.general.help',
            [
                'file' => $file
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getFaqs()
    {
        return FaqDefault::orderBy('order', 'asc')->get();
    }

    /**
     * @param $file
     * @return array
     */
    protected function moveFile($file)
    {
        $path = "uploads/help-files/default/";
        $file_dir = public_path($path);
        $file_path = $file_dir . sha1($file->getClientOriginalName()) . '.pdf';

        if (!\File::isDirectory($file_dir)) {
            \File::makeDirectory($file_dir, 493, true);
        }

        \File::put($file_path, \File::get($file));

        $fileDetails = [
            'name' => $file->getClientOriginalName(),
            'file_path' => $file_path,
            'file_url' => url($path . sha1($file->getClientOriginalName()) . '.pdf')
        ];

        return $fileDetails;
    }

    /**
     * @param Requests\SaveDefaultFAQFileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveFile(Requests\SaveDefaultFAQFileRequest $request)
    {
        $file = $request->file('file');
        $file2 = $request->file('file2');

        $fileDetails = [];

        if ($file) {
            $fileDetails = $this->moveFile($file);
        }

        if ($file2) {
            $moved = $this->moveFile($file2);

            $fileDetails = array_merge(
                $fileDetails,
                [
                    'police_file_path' => $moved['file_path'],
                    'police_file_url' => $moved['file_url'],
                    'police_name' => $moved['name']
                ]
            );
        }

        $fileDetails['type'] = 'pdf';
        $fileDetails['description'] = $request->get('description');
        $fileDetails['school_id'] = \Shelter::getID();

        \Session::flash('status', \Lang::get('toast.contents.admin.faq.settings_saved'));

        /**
         * @var $file \BComeSafe\Models\HelpFileDefault
         */
        $file = HelpFileDefault::first();
        if (!$file) {
            HelpFileDefault::create($fileDetails);
            return back();
        }

        $file->update($fileDetails);
        $file->save();

        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postSaveFaqItem(Request $request)
    {
        $data = $request->only(['id', 'question', 'answer', 'order', 'visible']);

        if ($data['id']) {
            $item = FaqDefault::find($data['id']);
            $item->update($data);
        } else {
            $item = FaqDefault::create($data);
        }

        return $item;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postRemoveFaqItem(Request $request)
    {
        $item = FaqDefault::find($request->get('id'));
        if ($item) {
            $item->delete();
        }

        return $item;
    }

    /**
     * @param Request $request
     */
    public function postSaveFaqOrder(Request $request)
    {
        $list = $request->all();

        foreach ($list as $position => $id) {
            if ($id) {
                FaqDefault::find($id)->update(['order' => $position]);
            }
        }
    }

    /**
     * Push notifications
     */

    public function getNotifications()
    {
        return view('system.general.notifications');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNotificationList()
    {
        return PushNotificationMessageDefault::all();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postRemovePushNotification(Request $request)
    {
        $item = PushNotificationMessageDefault::find($request->get('id'));
        if ($item) {
            $item->delete();
        }

        return $item;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postSavePushNotification(Request $request)
    {
        $data = $request->only(['id', 'label', 'content']);

        if ($data['id']) {
            $item = PushNotificationMessageDefault::find($data['id']);
            $item->update($data);
        } else {
            $item = PushNotificationMessageDefault::create($data);
        }

        return $item;
    }

    /**
     * SMS
     */

    public function getSms()
    {
        $sms = SmsMessageDefault::first();
        if (!$sms) {
            $sms = SmsMessageDefault::firstOrNew(
                [
                    'initial_message' => '',
                    'crisis_team_message' => '',
                ]
            );
        }

        return view('system.general.sms', ['sms' => $sms]);
    }

    /**
     * @param Requests\SaveSmsMessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveSms(Requests\SaveSmsMessageRequest $request)
    {
        $data = $request->only(
            [
                'initial_message',
                'crisis_team_message',
            ]
        );

        $sms = SmsMessageDefault::first();

        if (!$sms) {
            $sms = SmsMessageDefault::firstOrNew($data);
        } else {
            $sms->update($data);
        }

        $sms->save();

        return back();
    }
}
