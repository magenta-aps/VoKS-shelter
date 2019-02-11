<?php

namespace UCP\HttpClient;

/**
 * Http client interface
 */
interface ClientInterface
{
    /**
     * Authentication
     *
     * @param string $username User name
     * @param string $password User password
     *
     * @return boolean
     */
    public function authenticate($username, $password);

    /**
     * Send a POST request.
     *
     * @param string $uri     Request path
     * @param array  $headers Request headers
     * @param array  $body    Request body
     *
     * @return Response
     */
    public function post($uri, $headers = array(), $body = null);
    
    /**
     * Create a request
     * 
     * @param string $method  Request method, GET by default
     * @param string $uri     Request path
     * @param array  $headers Request headers
     * @param array  $body    Request body
     * 
     * @return Response
     */
    public function request($method = 'GET', $uri, $headers = array(), $body = null);
}