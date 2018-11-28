<?php namespace Aftermarketpl\Api;

/**
 * Class to connect to the AfterMarket.pl API server.
 *
 * @author MichalPleban
 */
class Client
{
    const DEFAULT_URL = "https://api.aftermarket.pl";

    /**
     * @var string $apiUrl the location of the API server.
     */
    private $apiUrl = self::DEFAULT_URL;

    /**
     * @var string $apiKey the public key used to connect to the API server.
     */
    private $apiKey = '';

    /**
     * @var string $apiSecret the secret key used to connect to the API server.
     */
    private $apiSecret = '';

    /**
     * @var boolean $apiDebug enable HTTPS traffic debugging.
     */
    private $apiDebug = false;

    /**
     * Class constructor. 
     *
     * Creates a new object to connect to the API.
     *
     * @param array $options the options for connectint to the API - see the README file.
     */
    public function __construct($options = array())
    {
        foreach($options as $key => $val)
        {
            switch($key)
            {
                case "url":
                    $this->apiUrl = $val;
                    break;
                case "key":
                    $this->apiKey = $val;
                    break;
                case "secret":
                    $this->apiSecret = $val;
                    break;
                case "debug":
                    $this->apiDebug = $val;
                    break;
            }
        }
    }
    
    /**
     * Send the API command to the server.
     *
     * @param string $command name of the command to send, for example "/domain/add".
     * @param array $params parameters of the command.
     *
     * @return mixed response from the API server.
     */
    public function send($command, $params = array())
    {
        $this->preCheck($command, $params);

        $url = $this->apiUrl . $command;
        if(class_exists("\GuzzleHttp\Client"))
        {
            $ret = $this->sendGuzzle($url, $params, false);
        }
        else
        {
            $ret = $this->sendCurl($url, $params);
        }
        $json = json_decode($ret);

        $this->postCheck($json);

        return $json->data;
    }
    
    /**
     * Send the API command to the server asynchronously.
     *
     * @param string $command name of the command to send, for example "/domain/add".
     * @param array $params parameters of the command.
     *
     * @return mixed response from the API server.
     */
    public function sendAsync($command, $params = array())
    {
        $this->preCheck($command, $params);

        $url = $this->apiUrl . $command;
        $promise = $this->sendGuzzle($url, $params, true);
        $me = $this;
        $ret = $promise->then(
            function($response) use(&$me)
            {
                $json = json_decode($response->getBody());
                $me->postCheck($json);
                return $json->data;
            },
            function($exception)
            {
                throw new Exception\ConnectionException($exception->getMessage());
            }
        );
        
        return $ret;
//        $ret = new Promise($promise, $this);
//        return $ret;
    }
    
    private function sendGuzzle($url, $params, $async = false)
    {
        try
        {
            $client = new \GuzzleHttp\Client();
            $params = array(
                "form_params" => $params,
                "auth" => array($this->apiKey, $this->apiSecret),
                "debug" => $this->apiDebug,
            );
            if(!$async)
            {
                $response = $client->request("POST", $url, $params);
                return $response->getBody();
            }
            else
            {
                $promise = $client->requestAsync("POST", $url, $params);
                return $promise;
            }
        }
        catch(Exception $e)
        {
            throw new Exception\ConnectionException($e->getMessage());
        }
    }
    
    private function sendCurl($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->apiDebug);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $ret = curl_exec($ch);
        
        $errNo = curl_errno($ch);
        if($errNo)
        {
            $errMsg = curl_error($ch);
            curl_close($ch);
            throw new Exception\ConnectionException($errMsg);
        }

        curl_close($ch);
        
        return $ret;
    }
    
    public function preCheck($command, $params)
    {
        if(!preg_match("|^(/[a-z]+)+$|", $command))
            throw new Exception\InvalidMethodException("Malformed command name: " . $command);
            
        if(!$this->apiKey || !$this->apiSecret)
            throw new Exception\AuthenticationException("Missing authentication data");
        
        if(!filter_var($this->apiUrl))
            throw new Exception\ConnectionException("Malformed API URL: " . $this->apiUrl);
        $protocol = parse_url($this->apiUrl, PHP_URL_SCHEME);
        if($protocol != "http" && $protocol != "https")
            throw new Exception\ConnectionException("Malformed API URL: " . $this->apiUrl);
    }
    
    public function postCheck($json)
    {
        if(!$json->ok)
        {
            switch($json->status)
            {
                case 401:
                    throw new Exception\InvalidStatusException($json->error, $json);
                case 402:
                    throw new Exception\InsufficientBalanceException($json->error, $json);
                case 403:
                    throw new Exception\AccessDeniedException($json->error, $json);
                case 404:
                    throw new Exception\NotFoundException($json->error, $json);
                case 405:
                    throw new Exception\DomainOperationException($json->error, $json);
                case 500:
                    throw new Exception\AuthenticationException($json->error, $json);
                case 501:
                    throw new Exception\InvalidMethodException($json->error, $json);
                case 502:
                    throw new Exception\OperationLimitException($json->error, $json);
                default:
                    throw new Exception\GenericException($json->error, $json);
            }
        }
    }

    /**
     * Set authentication data to be used with the API calls.
     * 
     * @param string $key the public key.
     * @param string $secret the secret key.
     */
    public function setAuth($key, $secret)
    {
        $this->apiKey = $key;
        $this->apiSecret = $secret;
    }
    
    /**
     * Retrieve current API authentication public key.
     *
     * @return string the public key.
     */
    public function getAuthKey()
    {
        return $this->apiKey;
    }

    /**
     * Retrieve current API authentication secret key.
     *
     * @return string the secret key.
     */
    public function getAuthSecret()
    {
        return $this->apiSecret;
    }

    /**
     * Set the URL used to connect to the API.
     * 
     * @param string $url the URL.
     */
    public function setUrl($url)
    {
        $this->apiUrl = $url;
    }

    /**
     * Retrieve current API connection URL.
     *
     * @return string the API URL.
     */
    public function getUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Set the connection debug flag.
     * 
     * @param boolean $debug the debug flag.
     */
    public function setDebug($debug)
    {
        $this->apiDebug = $debug ? true : false;
    }

    /**
     * Retrieve current debug flag.
     *
     * @return boolean the debug flag.
     */
    public function getDebug()
    {
        return $this->apiDebug;
    }
}