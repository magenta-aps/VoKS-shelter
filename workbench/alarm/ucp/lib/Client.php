<?php

namespace UCP;

use UCP\Api\ApiInterface;

use UCP\HttpClient\Client as HttpClient;
use UCP\HttpClient\ClientInterface as HttpClientInterface;

/**
 * UCP Client class
 * Responsible for wiring everything together.
 * 
 * @todo Caching
 * @todo Unit tests
 */
class Client
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * Client options
     *
     * @var array
     */
    private $options = [
        'debug'         => false,

        'node_id'       => 0,

        'httpClient' => [
            'debug'         => false,

            'base_uri'      => 'http://localhost',

            'user_agent'    => 'php-ucp-api',

            'digest' => [
                'username' => null,
                'password' => null,

                'realm'    => null,
                'uri'      => null,
                'nonce'    => null
            ]
        ]
    ];
    
    /**
     * Client constructor.
     * May be used to override default config.
     *
     * @param Array $options
     */
    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     * API access
     *
     * @param string $name Name of the API
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name)
    {
        $options = $this->options;
        switch ($name) {
            case 'auth':
                $api = new Api\Authentication($this, $options);
                break;

            case 'node':
                $api = new Api\Node($this, $options);
                break;

            case 'media':
                $api = new Api\Media($this, $options);
                break;
            
            case 'message':
                $api = new Api\Message($this, $options);
                break;
            
            case 'broadcast':
                $api = new Api\Broadcast($this, $options);
                break;
            
            case 'group':
                $api = new Api\BroadcastGroup($this, $options);
                break;
            
            default:
                throw new \InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
                break;
        }
        
        return $api;
    }
    
    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient($this->options['httpClient']);
        }
        return $this->httpClient;
    }
    
    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    
    /**
     * @param string $name
     * @param array  $args
     *
     * @throws BadMethodCallException
     *
     * @return ApiInterface
     */
    public function __call($name, array $args = array())
    {
        try {
            return $this->api($name);
        } catch (\InvalidArgumentException $e) {
            throw new \BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}