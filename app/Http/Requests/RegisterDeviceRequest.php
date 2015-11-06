<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Requests;

class RegisterDeviceRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'device_type' => ['required'],
            'device_id' => ['required'],
            'push_notification_id' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
            'mac_address' => ['required']
        ];

        return [
            'device_type' => ['required'],
            'device_id' => ['required'],
        ];
    }
}
