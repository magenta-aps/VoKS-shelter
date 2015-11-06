<?php

namespace UCP\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

use GuzzleHttp\Exception\ClientException as HttpClientException;

/**
 * Http client
 */
class Client implements ClientInterface
{
    /**
     * @var array
     */
    private $options = [
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
    ];
    
    /**
     * Http client constructor
     * 
     * @param array           $options HTTP Client options
     * @param ClientInterface $client  Custom HTTP client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);
        $this->client = $client ? : new GuzzleClient($this->options);
    }

    /**
     * Ping
     *
     * @throws HttpClientException
     */
    private function ping()
    {
        $method = 'AuthenticationManager.ping';
        $headers = [];
        $body = json_encode([
            'method'  => $method,
            'params'  => [],
        ]);

        try {
            $this->post('', $headers, $body);
        } catch (HttpClientException $ex) {
            $request = $ex->getRequest();
            $response = $ex->getResponse();

            $status = $response->getStatusCode();

            if (401 === $status) {
                // Unauthorized, parse digest
                $authRequired = $response->getHeader('auth-required');
                preg_match_all('/="([\/a-zA-Z0-9]+)"/', $authRequired[0], $authParsed);
                list ($digestRealm, $digestUri, $digestNonce) = $authParsed[1];

                $this->options['digest']['realm'] = $digestRealm;
                $this->options['digest']['uri']   = $digestUri;
                $this->options['digest']['nonce'] = $digestNonce;
            } else {
                throw new HttpClientException('Client error: ' . $status, $request, $response);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate($username, $password)
    {
        // Ping the server to get authorization digest
        $this->ping();

        // Authenticate
        $method = 'AuthenticationManager.login';
        $headers = [
            'Authorization' => $this->getAuthorizationHeader($method),
        ];
        $body = json_encode([
            'method'  => $method,
            'params'  => [$username, $password],
        ]);

        $response = $this->post('', $headers, $body);
        return $response;
    }

    /**
     * Gets authorization header
     *
     * @param string $method Method name
     *
     * @return string
     */
    public function getAuthorizationHeader($method)
    {
        $data = [
            'username'  => $this->options['digest']['username'],
            'realm'     => $this->options['digest']['realm'],
            'nonce'     => $this->options['digest']['nonce'],
            'uri'       => $this->options['digest']['uri'],
            'qop'       => 'auth',
            'response'  => $this->getAuthorizationHash($method)
        ];

        $map = array_map(function($val, $key) { return $key . '=' . $val; }, $data, array_keys($data));
        return 'Digest ' . implode(', ', $map);
    }

    /**
     * Gets authorization hash
     *
     * @param string $method Method name
     *
     * @return string MD5 hash
     */
    private function getAuthorizationHash($method)
    {
        $username = $this->options['digest']['username'];
        $password = $this->options['digest']['password'];

        $realm = $this->options['digest']['realm'];
        $uri = $this->options['digest']['uri'];
        $nonce = $this->options['digest']['nonce'];

        $ha1 = md5($username . ':' . $realm . ':' . $password);
        $ha2 = md5($method . ':' . $uri);

        return md5($ha1 . ':' . $nonce . ':' . $ha2);
    }

    /**
     * {@inheritDoc}
     */
    public function post($uri, $headers = array(), $body = null)
    {
        return $this->request('POST', $uri, $headers, $body);
    }

    /**
     * {@inheritDoc}
     */
    public function request($method = 'GET', $uri, $headers = array(), $body = null)
    {
        $request = new GuzzleRequest($method, $uri, $headers, $body);
        return $this->client->send($request);
    }
}