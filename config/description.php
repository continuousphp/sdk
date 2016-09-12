<?php
return [
    'baseUrl' => 'https://api.continuousphp.com/',
    'operations' => [
        'getCompanies' => [
            'httpMethod' => 'GET',
            'uri' => '/api/companies',
            'responseModel' => 'companyCollection'
        ],
        'getRepositories' => [
            'httpMethod' => 'GET',
            'uri' => '/api/repositories',
            'responseModel' => 'repositoryCollection'
        ],
        'createProject' => [
            'httpMethod' => 'POST',
            'uri' => '/api/projects',
            'responseModel' => 'project',
            'parameters' => [
                'provider' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => true
                ],
                'repository' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => true,
                    'sentAs' => 'url'
                ],
                'name' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => true
                ],
                'description' => [
                    'type' => 'string',
                    'location' => 'json'
                ]
            ]
        ],
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
        'getPipelines' => [
            'extends' => 'getProject',
            'uri' => '/api/projects/{provider}%2F{repository}/settings',
            'responseModel' => 'pipelineCollection'
        ],
        'putPipeline' => [
            'httpMethod' => 'PUT',
            'extends' => 'getProject',
            'uri' => '/api/projects/{provider}%2F{repository}/settings/{ref}',
            'responseModel' => 'pipeline',
            'parameters' => [
                'ref' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ],
                'phpVersions' => [
                    'type' => 'array',
                    'location' => 'json',
                    'required' => true
                ],
                'preparePhingTargets' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'preparePhingVariables' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'prepareShellCommands' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'preDeployPhingTargets' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'preDeployPhingVariables' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'preDeployShellCommands' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'deployOnSuccess' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'createGitHubRelease' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'packageRootPath' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'composerPath' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'enableComposerCache' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'runComposerHooksInPrepare' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'runComposerHooksInPackage' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'phingPath' => [
                    'type' => 'string',
                    'location' => 'json'
                ],
                'enabledTests' => [
                    'type' => 'array',
                    'location' => 'json',
                    'required' => true
                ],
                'deployment' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'notificationHooks' => [
                    'type' => 'array',
                    'location' => 'json'
                ],
                'credentials' => [
                    'type' => 'array',
                    'location' => 'json'
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
        'companyCollection' => [
            'type' => 'object',
            'additionalProperties' => [
                'location' => 'json'
            ]
        ],
        'repositoryCollection' => [
            'type' => 'object',
            'additionalProperties' => [
                'location' => 'json'
            ]
        ],
        'projectCollection' => [
            'type' => 'object',
            'additionalProperties' => [
                'location' => 'json'
            ]
        ],
        'pipelineCollection' => [
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
        'pipeline' => [
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