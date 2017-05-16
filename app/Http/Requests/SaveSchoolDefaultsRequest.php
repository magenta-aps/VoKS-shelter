<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Requests;

class SaveSchoolDefaultsRequest extends Request
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
        return [
            'timezone' => 'required',
            'locale' => 'required',
            'ordering' => 'required',
            // 'sms_provider' => 'required',
            // 'phone_system_provider' => 'required',
            // 'user_data_source' => 'required',
            // 'client_data_source' => 'required'
        ];
    }
}
