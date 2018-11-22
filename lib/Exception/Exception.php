<?php namespace Aftermarketpl\Api\Exception;

/**
*  Base exception class
*
*  @author MichalPleban
*/
class Exception extends \Exception
{
    /**
     * @var mixed $result The JSON response from the server.
     */
    private $response;
    
    /**
     * The class constructor.
     *
     * @param string $message Error message.
     * @param mixed $response The JSON response from the server.
     */
    public function __construct($message, $response = null)
    {
        parent::__construct($message);
        $this->response = $response;
    }
    
    /**
     * Return the JSON response from the server.
     *
     * @return mixed The JSON response.
     */
    public function getResponse()
    {
        return $this->response;
    }
}

