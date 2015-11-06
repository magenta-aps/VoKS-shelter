<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */


namespace BComeSafe\Libraries;

/**
 * Class CurlRequest
 *
 * @package BComeSafe\Libraries
 */
class CurlRequest
{

    /**
     *
     *
     * @type array
     */
    protected $curlOptions = [
        CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
        CURLOPT_FOLLOWLOCATION => 'CURLOPT_FOLLOWLOCATION',
        CURLOPT_HEADER => 'CURLOPT_HEADER',
        CURLOPT_SSL_VERIFYHOST => 'CURLOPT_SSL_VERIFYHOST',
        CURLOPT_SSL_VERIFYPEER => 'CURLOPT_SSL_VERIFYPEER',
        CURLOPT_URL => 'CURLOPT_URL',
        CURLOPT_POST => 'CURLOPT_POST',
        CURLOPT_POSTFIELDS => 'CURLOPT_POSTFIELDS',
        CURLOPT_HTTPHEADER => 'CURLOPT_HTTPHEADER',
        CURLOPT_COOKIEJAR => 'CURLOPT_COOKIEJAR',
        CURLOPT_COOKIEFILE => 'CURLOPT_COOKIEFILE',
        CURLOPT_COOKIESESSION => 'CURLOPT_COOKIESESSION',
        CURLOPT_CUSTOMREQUEST => 'CURLOPT_CUSTOMREQUEST',
        CURLOPT_USERPWD => 'CURLOPT_USERPWD'
    ];

    /**
     *
     */
    const PLAIN_RESPONSE = 1;
    /**
     *
     */
    const JSON_RESPONSE = 2;
    /**
     *
     */
    const CUSTOM_RESPONSE = 3;

    /**
     * @var array
     */
    protected $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CONNECTTIMEOUT => 5
    ];

    /**
     * @var int
     */
    protected $expectedResponseType = self::PLAIN_RESPONSE;
    /**
     * @var
     */
    protected $expectedResponseCallback;

    /**
     * @var
     */
    protected $requestUrl;

    /**
     * @param       $url
     * @param array $parameters
     *
     * @return $this
     */
    public function setUrl($url, $parameters = [])
    {
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        $this->options[CURLOPT_URL] = $url;

        return $this;
    }

    /**
     * @return $this
     */
    public function setJsonRequest()
    {
        $this->options[CURLOPT_HTTPHEADER] = [
            'Content-Type: application/json'
        ];

        return $this;
    }

    /**
     * @param $parameters
     *
     * @return $this
     */
    public function setPostRequest($parameters)
    {
        if (is_array($parameters)) {
            $parameters = http_build_query($parameters);
        }
        $this->options[CURLOPT_POST] = true;
        $this->options[CURLOPT_POSTFIELDS] = $parameters;

        return $this;
    }

    /**
     * @param $cookieJar
     *
     * @return $this
     */
    public function setCookieJar($cookieJar)
    {
        $this->options[CURLOPT_COOKIEJAR] = $cookieJar;
        $this->options[CURLOPT_COOKIEFILE] = $cookieJar;
        $this->options[CURLOPT_COOKIESESSION] = true;

        return $this;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setRequestMethod($type)
    {
        $this->options[CURLOPT_CUSTOMREQUEST] = $type;

        return $this;
    }

    /**
     * @param $username
     * @param $password
     *
     * @return $this
     */
    public function setAuthentication($username, $password)
    {
        $this->options[CURLOPT_USERPWD] = $username . ':' . $password;

        return $this;
    }

    /**
     * @param $options
     *
     * @return $this
     */
    public function setCurlOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @param $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        if (isset($this->options[CURLOPT_HTTPHEADER])) {
            $this->options[CURLOPT_HTTPHEADER] = array_merge($this->options[CURLOPT_HTTPHEADER], $headers);
        } else {
            $this->options[CURLOPT_HTTPHEADER] = $headers;
        }

        return $this;
    }

    /**
     * @param int      $type
     * @param callable $callback
     *
     * @return $this
     */
    public function expect($type = self::PLAIN_RESPONSE, \Closure $callback = null)
    {
        $this->expectedResponseType = $type;
        $this->expectedResponseCallback = $callback;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function debug()
    {
        $options = [];

        foreach ($this->options as $option => $value) {
            if (isset($this->curlOptions[$option])) {
                $options[$this->curlOptions[$option]] = $value;
            }
        }

        return $options;
    }

    /**
     * @return mixed
     * @throws CurlRequestException
     */
    public function execute()
    {
        $request = curl_init();

        //set cURL options
        curl_setopt_array($request, $this->options);

        //execute request and assign response
        $response = curl_exec($request);

        //render the response based on the expected response type
        switch ($this->expectedResponseType) {
            case self::JSON_RESPONSE:
                $response = json_decode(str_replace('NaN,', '0,', (string)$response), true);
                break;
        }

        if (is_callable($this->expectedResponseCallback)) {
            $response = call_user_func_array($this->expectedResponseCallback, [$response]);
        }

        //if cURL returned an error then throw an exception
        if (curl_errno($request)) {
            throw new CurlRequestException('Request to ' . $this->options[CURLOPT_URL] . ' failed. Error: ' . curl_error($request));
        }

        curl_close($request);

        return $response;
    }
}
