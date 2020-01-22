<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Aruba\Clearpass;

    use BComeSafe\Libraries\CurlRequest;
    use SoapBox\Formatter\Formatter;

    /**
     * Class User
     *
     * @package BComeSafe\Packages\Aruba\Clearpass
     */
class User
{
    /**
         * @type array
         */
    protected $options = [];

    /**
         * @param array $options
         */
    public function __construct($options = [])
    {
        $options = [
            'username'  => config('aruba.clearpass.login.username'),
            'password'  => config('aruba.clearpass.login.password'),
            'endpoints' => [
                'profile' => config('aruba.clearpass.user.profile'),
                'device'  => config('aruba.clearpass.user.device')
            ]
        ];

        $this->options = $options;
    }

    /**
         * @param $macAddress
         *
         * @return mixed
         * @throws \BComeSafe\Libraries\CurlRequestException
         */
    public function fetchProfile($macAddress)
    {
        $postData = $this->renderTemplate(
            'user-profile',
            [
            'mac' => strtolower(str_replace(':', '', $macAddress))
            ]
        );

        $curl = new CurlRequest();
        $curl->setHeaders(
            [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($postData)
            ]
        );

        $response = $curl->setUrl($this->options['endpoints']['profile'])
            ->setPostRequest($postData)
            ->setAuthentication($this->options['username'], $this->options['password'])
            ->expect(
                CurlRequest::CUSTOM_RESPONSE,
                function ($data) {
                    $profile = [];
                    try {
                        $data = Formatter::make($data, Formatter::XML)->toArray();
                    } catch (\ErrorException $e) {
                        $data = [];
                    }
                    $tags = array_get($data, 'Endpoints.Endpoint.EndpointTags');
                    if (empty($tags)) {
                        return [
                        'username' => null,
                        'name' => null
                        ];
                    }
                    foreach ($tags as $tag) {
                        $value = $tag['@attributes']['tagValue'];

                        switch ($tag['@attributes']['tagName']) {
                            case 'Display Name':
                                $profile['fullname'] = $value;
                                break;
                        }
                    }

                    return $profile;
                }
            )->execute();

            return $response;
    }

    /**
         * @param $ipAddress
         *
         * @return null
         * @throws \BComeSafe\Libraries\CurlRequestException
         */
    public function fetchMacAddress($ipAddress)
    {
        $curl = new CurlRequest();
        $response = $curl->setUrl($this->options['endpoints']['device'] . $ipAddress)
            ->setAuthentication($this->options['username'], $this->options['password'])
            ->expect(CurlRequest::JSON_RESPONSE)->execute();
        if (isset($response['mac'])) {
            return $response['mac'];
        }

        return null;
    }

    /**
         * @param $file
         * @param $data
         *
         * @return mixed|string
         */
    protected function renderTemplate($file, $data)
    {
        $xml = \File::get(__DIR__ . '/requests/' . $file . '.xml');
        foreach ($data as $variable => $value) {
            $xml = str_replace('{{ $' . $variable . ' }}', $value, $xml);
        }

        return $xml;
    }

    /**
         * @param $ipAddress
         *
         * @return array|mixed
         */
    public function getByIp($ipAddress)
    {

        $profile = [];
        $mac = $this->fetchMacAddress($ipAddress);
        if (!empty($mac)) {
            $profile = $this->getByMac($mac);
        }

        return $profile;
    }

    public function getByMac($macAddress)
    {
        $profile = $this->fetchProfile($macAddress);
        $profile['mac_address'] = strtoupper(format_mac_address($macAddress));

        return $profile;
    }
}
