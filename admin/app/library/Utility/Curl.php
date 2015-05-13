<?php

# Curl, CurlResponse
#
# Author Sean Huber - shuber@huberry.com
# Date May 2008
#
# A basic CURL wrapper for PHP
#
# See the README for documentation/examples or g for more information about the libcurl extension for PHP

class Curl extends \Phalcon\Mvc\User\Component
{
    public $cookieFile  = '/tmp/curl.cookie.txt';
    public $cookieKey   = '';
    public $cookieName  = '';
    public $headers     = array();
    public $options     = array();
    public $referer     = '';
    public $user_agent  = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36';
    public $interface   = '';

    protected $error = '';
    protected $handle;

    public function unsetCookie() {
        if (file_exists($this->cookieFile)) {
            unlink($this->cookieFile);
        }
    }

    public function delete($url, $vars = array())
    {
        return $this->request('DELETE', $url, $vars);
    }

    public function error()
    {
        return $this->error;
    }

    public function get($url, $vars = array(), $isSetCookie = false, $sslVersion = "", $isFollowLocation = true)
    {
        if (!empty($vars)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= http_build_query($vars, '', '&');
        }
        return $this->request('GET', $url, $vars, $isSetCookie, $sslVersion, $isFollowLocation);
    }

    public function post($url, $vars = array(), $isSetCookie = false, $sslVersion = "", $isFollowLocation = true)
    {
        return $this->request('POST', $url, $vars, $isSetCookie, $sslVersion, $isFollowLocation);
    }

    public function put($url, $vars = array(), $isSetCookie = false, $sslVersion = "", $isFollowLocation = true)
    {
        return $this->request('PUT', $url, $vars, $isSetCookie, $sslVersion, $isFollowLocation);
    }

    protected function request($method, $url, $vars = array(), $isSetCookie = false, $sslVersion = "", $isFollowLocation = true)
    {
        $this->handle = curl_init();

        # Set some default CURL options
        curl_setopt($this->handle, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($this->handle, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($this->handle, CURLOPT_COOKIESESSION, true);
        if ($this->cookieKey != "") {
            curl_setopt($this->handle, CURLOPT_COOKIE, $this->cookieKey . '=' . $this->cookieName . '; path=/');
        }
//        curl_setopt($this->handle, CURLOPT_ENCODING, $this->encoding);
        curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, $isFollowLocation);
        if ($isSetCookie) {
            curl_setopt($this->handle, CURLOPT_HEADER, true);
        } else {
            curl_setopt($this->handle, CURLOPT_HEADER, false);
        }
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, (is_array($vars) ? http_build_query($vars, '', '&') : $vars));
        curl_setopt($this->handle, CURLOPT_REFERER, $this->referer);

        //TRUE to automatically set the Referer
        curl_setopt($this->handle, CURLOPT_AUTOREFERER, TRUE);

        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_USERAGENT, $this->user_agent);

        if (isset ( $this->interface ) && $this->interface != false) {
            curl_setopt ( $this->handle, CURLOPT_INTERFACE, $this->interface);
        }

        if ($sslVersion != '') {
            curl_setopt($this->handle, CURLOPT_SSLVERSION,$sslVersion);
        }

        /**
         * My CUSTOM HEADER CALLBACK
         */
        curl_setopt($this->handle, CURLOPT_HEADERFUNCTION, "Curl::curlHeaders");

        # Format custom headers for this request and set CURL option
        $headers = array();
        foreach ($this->headers as $key => $value) {
            if ($value != "") $headers[] = $key.': '.$value;
        }
//        Zend_Debug::dump($headers);exit;
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);

        # Determine the request method and set the correct CURL option
        switch ($method) {
            case 'GET':
                curl_setopt($this->handle, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->handle, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, $method);
        }

        # Set any custom CURL options
        foreach ($this->options as $option => $value) {
            curl_setopt($this->handle, constant('CURLOPT_'.str_replace('CURLOPT_', '', strtoupper($option))), $value);
        }

        $response = curl_exec($this->handle);
        if (!$response) {
            $this->error = curl_errno($this->handle).' - '.curl_error($this->handle);
        }

//        if ($isSetCookie) {
//            preg_match('/^Set-Cookie:\s*([^;]*)/mi', $response, $m);
//            Zend_Debug::dump($m);exit;
//            curl_setopt($this->handle, CURLOPT_COOKIE, current(parse_url($m[1])) . '; path=/');
//        }


        curl_close($this->handle);
        return $response;
    }

    /**
     * Close the curl resource
     */
    public function close() {
        if ($this->handle) {
            curl_close($this->handle);
        }
    }
    /**
     * Set the ip/interface for downloading
     */
    public function setInterface($interface) {
        $this->interface = $interface;
    }

    /**
     * Curl Header function use for debugging
     *
     * @param curl resource $ch
     * @param string $header
     * @return int
     */
    public static function curlHeaders($ch, $header) {
        if ($ch) {
            //echo $header;
            return strlen($header);
        } else {
            echo "curl not initialized\n";
            return false;
        }
    }
}


