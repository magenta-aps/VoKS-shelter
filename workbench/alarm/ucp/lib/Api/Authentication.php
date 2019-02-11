<?php

namespace UCP\Api;

use UCP\HttpClient\HttpAuth;

/**
 * Authentication API
 */
class Authentication extends AbstractApi
{
    /**
     * Login using credentials provided
     *
     * ---------------------------------------------------------------
     * Method: AuthenticationManager.login
     * Params: username, password
     * ---------------------------------------------------------------
     * 
     * @param string $username Username
     * @param string $password Password
     * 
     * @return bool
     */
    public function login($username, $password)
    {
        $this->client->getHttpClient()->authenticate($username, $password);
        return true;
    }
}
