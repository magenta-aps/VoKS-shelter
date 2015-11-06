<?php

namespace UCP\Api;

use UCP\Client;

/**
 * Abstract class for Api classes.
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * UCP Client class
     *
     * @var Client
     */
    protected $client;
    
    /**
     * @var array
     */
    protected $options = [];
    
    /**
     * @param Client $client
     * @param array  $options
     */
    public function __construct(Client $client, array $options)
    {
        $this->options = array_merge($this->options, $options);
        $this->client = $client;
    }

    /**
     * POST method
     *
     * @param string $method UCP method
     * @param array  $params UCP parameters
     *
     * @return Response
     */
    public function post($method, $params = array())
    {
        $httpClient = $this->client->getHttpClient();

        $headers = [
            'Authorization' => $httpClient->getAuthorizationHeader($method),
        ];
        $body = json_encode([
            'method'  => $method,
            'params'  => $params,
        ]);

        return $httpClient->post('', $headers, $body);
    }
}
