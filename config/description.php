<?php
return [
    'baseUrl' => 'https://api.continuousphp.com/',
    'operations' => [
        'getProjects' => [
            'httpMethod' => 'GET',
            'uri' => '/api/projects',
            'responseModel' => 'projectCollection'
        ],
        'getProject' => [
            'httpMethod' => 'GET',
            'uri' => '/api/projects/{provider}%2F{repository}',
            'responseModel' => 'project',
            'parameters' => [
                'provider' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ],
                'repository' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
        'getBuilds' => [
            'extends' => 'getProject',
            'uri' => '/api/projects/{provider}%2F{repository}/builds',
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
                    'location' => 'query'
                ]
            ]
        ],
        'getBuild' => [
            'extends' => 'getProject',
            'uri' => '/api/projects/{provider}%2F{repository}/builds/{buildId}',
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
            'uri' => '/api/projects/{provider}%2F{repository}/builds/{buildId}/packages/{packageType}',
            'responseModel' => 'build',
            'parameters' => [
                'packageType' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true,
                    'enum' => ['deploy', 'test']
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
];