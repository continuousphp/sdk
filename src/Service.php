<?php
/**
 * Service.php
 *
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Service.php
 * @link      http://github.com/continuousphp/sdk the canonical source repo
 */

namespace Continuous\Sdk;

use Continuous\Sdk\Client as ContinuousClient;

/**
 * Service
 *
 * @package   Continuous\Sdk
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Service
{
    /**
     * @var string
     */
    protected static $httpClientClass;
    
    /**
     * @var string
     */
    protected static $descriptionClass;

    /**
     * @return string
     */
    public static function getHttpClientClass()
    {
        if (empty(self::$httpClientClass)) {
            self::setHttpClientClass('GuzzleHttp\Client');
        }
        
        return self::$httpClientClass;
    }

    /**
     * @param string $httpClientClass
     */
    public static function setHttpClientClass($httpClientClass)
    {
        if (!in_array('GuzzleHttp\ClientInterface', class_implements($httpClientClass))) {
            throw new \InvalidArgumentException("$httpClientClass does not implement GuzzleHttp\\ClientInterface");
        }
        
        self::$httpClientClass = $httpClientClass;
    }
    
    /**
     * @param array $config
     * @return \GuzzleHttp\ClientInterface
     */
    public static function getHttpClient(array $config = array())
    {
        $className = self::getHttpClientClass();
        
        $args = [
            'defaults' => [
                'query' => [],
                'headers' => [
                    'Accept' => 'application/hal+json'
                ]
            ]
        ];
        
        if (isset($config['token'])) {
            $args['defaults']['query']['token'] = $config['token'];
        }
        
        return new $className($args);
    }

    /**
     * @return string
     */
    public static function getDescriptionClass()
    {
        if (empty(self::$descriptionClass)) {
            self::setDescriptionClass('GuzzleHttp\Command\Guzzle\Description');
        }
        
        return self::$descriptionClass;
    }

    /**
     * @param string $descriptionClass
     */
    public static function setDescriptionClass($descriptionClass)
    {
        if (!in_array('GuzzleHttp\Command\Guzzle\DescriptionInterface', class_implements($descriptionClass))) {
            $message = "$descriptionClass does not implement GuzzleHttp\\Command\\Guzzle\\DescriptionInterface";
            throw new \InvalidArgumentException($message);
        }
        
        self::$descriptionClass = $descriptionClass;
    }
    
    /**
     * @return \GuzzleHttp\Command\Guzzle\DescriptionInterface
     */
    public static function getDescription()
    {
        $className = self::getDescriptionClass();
        
        return new $className(include __DIR__ . '/../config/description.php');
    }
    
    /**
     * @param array $config
     * @return ContinuousClient
     */
    public static function factory(array $config = [])
    {
        return new ContinuousClient(self::getHttpClient($config), self::getDescription());
    }
}
