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
use Continuous\Sdk\Entity\Build;
use Continuous\Sdk\Entity\Company;
use Continuous\Sdk\Entity\Package;
use Continuous\Sdk\Entity\Pipeline;
use Continuous\Sdk\Entity\Project;
use Continuous\Sdk\Entity\Repository;
use Continuous\Sdk\Entity\WebHooks;
use Continuous\Sdk\Entity\Reference;
use Continuous\Sdk\ResponseLocation\HalLocation;

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
            'headers' => [
                'Accept' => 'application/hal+json'
            ],
        ];

        if (isset($config['token'])) {
            $args['headers']['Authorization'] = "Bearer " . $config['token'];
        } elseif ($token = getenv('CPHP_TOKEN')) {
            $args['headers']['Authorization'] = "Bearer " . $token;
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
    public static function getDescription($baseUrl = null)
    {
        $className = self::getDescriptionClass();
        $description = include __DIR__ . '/../config/description.php';

        if ($baseUrl) {
            $description['baseUrl'] = $baseUrl;
        }

        return new $className($description);
    }

    /**
     * @param array $config
     * @param string $baseUrl the API Url that override description configuration
     * @return ContinuousClient
     */
    public static function factory(array $config = [], $baseUrl = null)
    {
        $locations = [
            'response_locations' => [
                'cphp-build' => new HalLocation('cphp-build', Build::class),
                'cphp-company' => new HalLocation('cphp-company', Company::class),
                'cphp-project' => new HalLocation('cphp-project', Project::class),
                'cphp-setting' => new HalLocation('cphp-setting', Pipeline::class),
                'cphp-repository' => new HalLocation('cphp-repository', Repository::class),
                'cphp-package' => new HalLocation('cphp-package', Package::class),
                'cphp-webhooks' => new HalLocation('cphp-webhooks', WebHooks::class),
                'cphp-reference' => new HalLocation('cphp-reference', Reference::class)
            ]
        ];

        return new ContinuousClient(
            self::getHttpClient($config),
            self::getDescription($baseUrl),
            null,
            null,
            null,
            $locations
        );
    }
}
