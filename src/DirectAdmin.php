<?php

namespace Trizz\DirectAdmin;

abstract class DirectAdmin
{
    /**
     * @var string The DirectAdmin username.
     */
    private $username;

    /**
     * @var string The DirectAdmin password.
     */
    private $password;

    /**
     * @var string The domain to use.
     */
    private $domain;

    /**
     * @var string The base URL of the DirectAdmin server.
     */
    private $baseUrl;

    /**
     * Construct the DaMailingList instance.
     *
     * @param string $host     The DirectAdmin host (including protocol).
     * @param string $username The DirectAdmin username.
     * @param string $password The DirectAdmin password.
     * @param string $domain   The domain to use.
     * @param int    $port     The DirectAdmin port (default: 2222).
     */
    public function __construct($host, $username, $password, $domain, $port = 2222)
    {
        // Format the base url.
        $this->baseUrl = sprintf(
            '%s:%d',
            $host,
            $port
        );

        // Store the "settings".
        $this->username = $username;
        $this->password = $password;
        $this->setDomain($domain);
    }

    /**
     * Update the domain to use. Can be used to switch to another domain with the same user credentials.
     *
     * @param string $domain Update the domain to use.
     *
     * @return $this The current instance.
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Post the $queryString to the specified $endPoint to the DirectAdmin server.
     *
     * @param string $endPoint    The DirectAdmin API endpoint.
     * @param array  $queryString Optional query string fields to post.
     *
     * @return array The DirectAdmin API response.
     */
    protected function sendRequest($endPoint, $queryString = null)
    {
        // Create a cookie jar.
        $cookieJar = tempnam('/tmp', 'daMailingList');
        // Make list of POST fields
        $fields = [
            'username' => urlencode($this->username),
            'password' => urlencode($this->password),
        ];

        // Add the domain to the query string (this one is always required).
        $queryString['domain'] = $this->domain;

        // Create a curl instance and login.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.'/CMD_LOGIN');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);

        if ($result === false) {
            die('CURL ERROR: '.curl_error($ch));
        } else {
            // If the login is successful, perform the "real" request.
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$endPoint.'?'.http_build_query($queryString));
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            if ($result === false) {
                die('CURL ERROR: '.curl_error($ch));
            } else {
                // Decode the response and parse it to an array.
                parse_str(urldecode($result), $returnArray);

                // Return the data.
                return $returnArray;
            }
        }
    }
}
