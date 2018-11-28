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
        $var->setAuth("key", "secret");
        $this->assertTrue($var->getAuthKey() == "key");
        $this->assertTrue($var->getAuthSecret() == "secret");
        unset($var);
    }

    public function testMethodGetAuthKey()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "getAuthKey"));
        $var->setAuth("key", "secret");
        $this->assertTrue($var->getAuthKey() == "key");
        unset($var);

        $var = new Aftermarketpl\Api\Client(array("key" => "key"));
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

        $var = new Aftermarketpl\Api\Client(array("secret" => "secret"));
        $this->assertTrue($var->getAuthSecret() == "secret");
        unset($var);
    }

    public function testMethodSetUrl()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "setUrl"));
        $var->setUrl("url");
        $this->assertTrue($var->getUrl() == "url");
        unset($var);
    }

    public function testMethodGetUrl()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "getUrl"));
        $var->setUrl("url");
        $this->assertTrue($var->getUrl() == "url");
        unset($var);

        $var = new Aftermarketpl\Api\Client(array("url" => "url"));
        $this->assertTrue($var->getUrl() == "url");
        unset($var);
    }

    public function testMethodSetDebug()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "setDebug"));
        $var->setDebug(true);
        $this->assertTrue($var->getDebug());
        unset($var);
    }

    public function testMethodGetDebug()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "getDebug"));
        $var->setDebug(true);
        $this->assertTrue($var->getDebug());
        unset($var);

        $var = new Aftermarketpl\Api\Client(array("debug" => true));
        $this->assertTrue($var->getDebug());
        unset($var);
    }

    public function testMethodSend()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "send"));
        unset($var);
    }

    public function testMethodSendAsync()
    {
        $var = new Aftermarketpl\Api\Client;
        $this->assertTrue(method_exists($var, "sendAsync"));
        unset($var);
    }

    public function testExceptionInvalidMethod()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\InvalidMethodException");
        $var = new Aftermarketpl\Api\Client(array(
            "key" => "key",
            "secret" => "secret",
        ));
        $var->send("test", array());
        unset($var);
    }

    public function testExceptionNoAuthenticationData()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\AuthenticationException");
        $var = new Aftermarketpl\Api\Client(array(
        ));
        $var->send("/test", array());
        unset($var);
    }

    public function testExceptionMalformedUrl()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\ConnectionException");
        $var = new Aftermarketpl\Api\Client(array(
            "url" => "incorrect",
            "key" => "key",
            "secret" => "secret",
        ));
        $var->send("/test", array());
        unset($var);
    }

    public function testExceptionWrongProtocol()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\ConnectionException");
        $var = new Aftermarketpl\Api\Client(array(
            "url" => "ftp://incorrect",
            "key" => "key",
            "secret" => "secret",
        ));
        $var->send("/test", array());
        unset($var);
    }

    public function testAsyncExceptionInvalidMethod()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\InvalidMethodException");
        $var = new Aftermarketpl\Api\Client(array(
            "key" => "key",
            "secret" => "secret",
        ));
        $var->sendAsync("test", array());
        unset($var);
    }

    public function testAsyncExceptionNoAuthenticationData()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\AuthenticationException");
        $var = new Aftermarketpl\Api\Client(array(
        ));
        $var->sendAsync("/test", array());
        unset($var);
    }

    public function testAsyncExceptionMalformedUrl()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\ConnectionException");
        $var = new Aftermarketpl\Api\Client(array(
            "url" => "incorrect",
            "key" => "key",
            "secret" => "secret",
        ));
        $var->sendAsync("/test", array());
        unset($var);
    }

    public function testAsyncExceptionWrongProtocol()
    {
        $this->expectException("\Aftermarketpl\Api\Exception\ConnectionException");
        $var = new Aftermarketpl\Api\Client(array(
            "url" => "ftp://incorrect",
            "key" => "key",
            "secret" => "secret",
        ));
        $var->sendAsync("/test", array());
        unset($var);
    }
}
