<?php

/**
 * Created by PhpStorm.
 * User: HaiLP
 * Date: 6/3/17
 * Time: 11:37 AM
 */
class Curl
{
    const VERSION = 0.1;
    const DEFAULT_TIMEOUT = 3000;

    public $curl;
    public $id = null;
    public $baseUrl = null;
    public $rawResponse = null;

    public function __construct($base_url = null)
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL library is not loaded');
        }

        $this->curl = curl_init();
    }

    private function setDefaultUserAgent()
    {
        $user_agent = 'PHP-Curl-Class/' . self::VERSION . ' (https://github.com/vietworm/php-curl-class)';
        $user_agent .= ' PHP/' . PHP_VERSION;
        $user_agent .= ' cURL/' . curl_version()['version'];
        curl_setopt($this->curl, CURLOPT_USERAGENT, $user_agent);
    }

    public function setBasicAuth($auth)
    {
        $http_header = array(
            "Cache-control: no-cache",
            "Content-Type: application/json;charset=UTF-8",
            "Authorization: " . $auth
        );

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $http_header);
    }

    public function get($url)
    {
        if (empty($url)) {
            $url = $this->baseUrl;
        }

        // make User-Agent for https required
        $this->setDefaultUserAgent();

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, self::DEFAULT_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, self::DEFAULT_TIMEOUT);

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 1);

        // make cURL follow a redirect, example: http -> https
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_HTTPGET, true);

        $this->rawResponse = curl_exec($this->curl);

        curl_close($this->curl);

        return $this->rawResponse;
    }
}