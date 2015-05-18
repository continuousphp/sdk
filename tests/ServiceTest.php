<?php
/**
 * ServiceTest.php
 *
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ServiceTest.php
 * @link      http://github.com/continuousphp/sdk the canonical source repo
 */

namespace Continuous\Tests\Sdk;

use Continuous\Sdk\Service;

/**
 * ServiceTest
 *
 * @package   Continuous\Sdk
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $httpClientMock;
    protected $originalHttpClientClass;
    
    public function setUp()
    {
        $this->httpClientMock = $this->getMock('GuzzleHttp\ClientInterface');
        $this->originalHttpClientClass = Service::getHttpClientClass();
    }
    
    public function tearDown()
    {
        Service::setHttpClientClass($this->originalHttpClientClass);
    }
    
    public function testHttpClientClassAccessor()
    {
        $originalClass = Service::getHttpClientClass();
        
        Service::setHttpClientClass(get_class($this->httpClientMock));
        $this->assertAttributeEquals(get_class($this->httpClientMock), 'httpClientClass', 'Continuous\Sdk\Service');
        Service::setHttpClientClass($originalClass);
        $this->assertAttributeEquals($originalClass, 'httpClientClass', 'Continuous\Sdk\Service');
    }
    
    public function testHttpClientClassSetterThrowsExceptionOnBadClassnameProvided()
    {
        $this->setExpectedException("InvalidArgumentException");
        Service::setHttpClientClass("stdClass");
    }
    
    public function testGetHttpClientSetCorrectlyAccessToken()
    {
        $token = 'my-awesome-token';
        $config = compact('token');
        
        Service::setHttpClientClass('GuzzleHttp\Client');
        
        $client = Service::getHttpClient($config);
        $this->assertInstanceOf('GuzzleHttp\Client', $client);
        
        $this->assertArrayHasKey('token', $client->getDefaultOption('query'));
        $this->assertEquals($token, $client->getDefaultOption('query')['token']);
    }
    
    public function testGetHttpClientHasNoTokenIfNotProvided()
    {
        Service::setHttpClientClass('GuzzleHttp\Client');
        
        $client = Service::getHttpClient();
        $this->assertInstanceOf('GuzzleHttp\Client', $client);
        
        $this->assertArrayNotHasKey('token', $client->getDefaultOption('query'));
    }

}