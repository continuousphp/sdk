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
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
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
     * @param array $config
     * @param array $globalParameters
     * @return ContinuousClient
     */
    public static function factory(array $config = array(), array $globalParameters = array())
    {
        if (isset($config['token'])) {
            $token = $config['token'];
        } else {
            throw new \InvalidArgumentException('API Token not provided');
        }
        
        $httpClient = new Client([
            'defaults' => [
                'query' => [
                    'token' => $token
                ],
                'headers' => [
                    'Accept' => 'application/hal+json'
                ]
            ]
        ]);
        $description = new Description([
            'baseUrl' => 'https://api.continuousphp.com/',
            'operations' => [
                'getProjects' => [
                    'httpMethod' => 'GET',
                    'uri' => '/api/projects',
                    'responseModel' => 'projectCollection'
                ],
                'getProject' => [
                    'httpMethod' => 'GET',
                    'uri' => '/api/projects/{projectId}',
                    'responseModel' => 'project',
                    'parameters' => [
                        'projectId' => [
                            'type' => 'string',
                            'location' => 'uri',
                            'required' => true
                        ]
                    ]
                ],
                'getBuilds' => [
                    'extends' => 'getProject',
                    'uri' => '/api/projects/{projectId}/builds',
                    'responseModel' => 'buildCollection',
                    'parameters' => [
                        'state' => [
                            'type' => 'array',
                            'location' => 'query',
                            'enum' => ['in-progress', 'complete', 'timeout']
                        ],
                        'result' => [
                            'type' => 'array',
                            'location' => 'query',
                            'enum' => ['success', 'warning', 'failed']
                        ],
                        'ref' => [
                            'type' => 'string',
                            'location' => 'query',
                            'filters' => ['urlencode']
                        ]
                    ]
                ],
                'getBuild' => [
                    'extends' => 'getProject',
                    'uri' => '/api/projects/{projectId}/builds/{buildId}',
                    'responseModel' => 'build',
                    'parameters' => [
                        'buildId' => [
                            'type' => 'string',
                            'location' => 'uri',
                            'required' => true
                        ]
                    ]
                ],
                'getPackage' => [
                    'extends' => 'getBuild',
                    'uri' => '/api/projects/{projectId}/builds/{buildId}/packages/{packageType}',
                    'responseModel' => 'build',
                    'parameters' => [
                        'packageType' => [
                            'type' => 'string',
                            'location' => 'uri',
                            'required' => true
                        ]
                    ]
                ]
            ],
            'models' => [
                'projectCollection' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ],
                'buildCollection' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ],
                'project' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ],
                'build' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ],
                'package' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ],
            ]
        ]);
        
        return new ContinuousClient($httpClient, $description);
    }
}
