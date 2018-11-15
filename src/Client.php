<?php namespace Aftermarketpl\Api;

/**
*  Class to connect to the AfterMarket.pl API server.
*
*  @author MichalPleban
*/
class Client
{

    const DEFAULT_URL = "https://api.aftermarket.pl";

    /**
     * @var string $apiUrl the location of the API server.
     */
    private $apiUrl = '';

    /**
     * @var string $apiKey the public key used to connect to the API server.
     */
    private $apiKey = '';

    /**
     * @var string $apiSecret the secret key used to connect to the API server.
     */
    private $apiSecret = '';

    /**
     * Class constructor. 
     *
     * Creates a new object to connecto to the API.
     *
     * @param string $url the URL of the API server. Leeave blank for production API access.
     */
    public function __construct($url = self::DEFAULT_URL)
    {
        $this->apiUrl = $url;
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
     * Send the API command to the server.
     *
     * @param string $command name of the command to send, for example "/domain/add".
     * @param array $params parameters of the command.
     *
     * @return mixed response from the API server.
     */
    public function send($command, $params = array())
    {
        $url = $this->apiUrl . $command;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $ret = curl_exec($ch);
        
        curl_close($ch);
        
        $json = json_decode($ret);
        
        return $json->data;
    }
}