<?php 

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testSyntax()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(is_object($var));
        unset($var);
    }

    public function testMethodSetAuth()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "setAuth"));
        unset($var);
    }

    public function testMethodGetAuthKey()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "getAuthKey"));
        $var->setAuth("key", "secret");
        $this->assertTrue($var->getAuthKey() == "key");
        unset($var);
    }

    public function testMethodGetAuthSecret()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "getAuthSecret"));
        $var->setAuth("key", "secret");
        $this->assertTrue($var->getAuthSecret() == "secret");
        unset($var);
    }

    public function testMethodSend()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "send"));
        unset($var);
    }
}
