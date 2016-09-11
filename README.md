# continuousphp SDK

[![Build Status](https://status.continuousphp.com/git-hub/continuousphp/sdk?token=9800bb61-98f2-447d-a331-025f0b9af298&branch=master)](https://continuousphp.com/git-hub/continuousphp/sdk)

continuousphp SDK enables PHP developers to use [continuousphp](https://continuousphp.com/) in their PHP code.

## Installation

Install this package through [Composer](https://getcomposer.org/) by adding this package in the require section

```json
"require": {
    "continuousphp/sdk": "~0.3"
}
```

## Usage

### Initialize the SDK
```php
<?php
require 'vendor/autoload.php';

$service = Continuous\Sdk\Service::factory();
```

Or use an access token to enable private data access
```php
<?php
require 'vendor/autoload.php';

$service = Continuous\Sdk\Service::factory(['token' => 'my-access-token']);
```

### Get your repository list
```php
$projects = $service->getRepositories();
```

### Get your project list
```php
$projects = $service->getProjects();
```

### Get a specific project
```php
$project = $service->getProject([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk'
]);
```

### Create a specific project
```php
$project = $service->createProject([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk'
]);
```

### Get the deployment pipelines of a specific project
```php
$project = $service->getPipelines([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk'
]);
```

### Put a deployment pipelines of a specific project
```php
$project = $service->putPipeline([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'ref' => 'refs/heads/master',
    'phpVersions' => [ '5.3.3', '5.3.max', '5.4.max', '5.5.max' ],
    'preparePhingTargets' => [ 'clean-up', 'setup-env', 'doctrine-proxies' ],
    'preDeployPhingTargets' => [ 'clean-up2', 'setup-env2', 'doctrine-proxies2' ],
    'preparePhingVariables' => [
        'env' => 'testing',
        'hostname' => 'site.com'
    'preDeployPhingVariables' => [
        'env' => 'production',
        'hostname' => 'site2.com'
    ],
    'prepareShellCommands => [
        'cd /path/to/folder1a; mkdir subfolder1a',
        'cd /path/to/folder1b; mkdir subfolder1b'
    ],
    'preDeployShellCommands' => [
        'cd /path/to/folder2a; mkdir subfolder2a',
        'cd /path/to/folder2b; mkdir subfolder2b'
    ],
    'deployOnSuccess' => '1',
    'createGitHubRelease' => '1',
    'packageRootPath' => '/path/to/package/root'
    'composerPath' => '/path/to/composer',
    'enableComposerCache' => '1',
    'phingPath' => '/path/to/phing'
    'enabledTests' => [
        [
            'type' => 'phpunit',
            'paths' => [ '/path/one', '/path/two' ],
            'blocking' => '1',
            'phingTargets' => [ 'reset-db', 'insert-fixtures' ],
            'phingVariables' => [
                'env' => 'testing',
                'hostname' => 'site.com'
            ],
            'shellCommands' => [
                'cd /path/to/folder3a; mkdir subfolder3a',
                'cd /path/to/folder3b; mkdir subfolder3b'
            ]
        ],
        [
            'type' => 'atoum',
            'paths' => [ '/path/three', '/path/four' ],
            'configFiles' => [ '/config/one', '/config/two' ],
            'bootstrapFile' => '/path/to/bootstrap/file',
            'blocking' => '0',
            'phingTargets' => [ 'reset-db2', 'insert-fixtures2' ],
            'phingVariables' => [
                'env' => 'staging',
                'hostname' => 'site2.com'
            ]
        ],
        [
            'type' => 'phpcs',
            'paths' => [ '/path/five', '/path/six' ],
            'blocking' => '0',
            'phingTargets' => [ 'reset-db2', 'insert-fixtures2' ],
            'phingVariables' => [
                'env' => 'staging',
                'hostname' => 'site3.com'
            ],
            'container' => '5.3.3'
        ],
        [
            'type' => 'codeception',
            'paths' => [ '/path/seven', '/path/eight' ],
            'blocking' => '1',
            'phingTargets => [ 'reset-db3', 'insert-fixtures3' ],
            'phingVariables' => [
                'env' => 'production',
                'hostname' => 'site4.com'
            ]
        ],
        [
            'type' => 'phpspec',
            'paths' => [ '/path/nine', '/path/ten' ],
            'blocking' => '1',
            'phingTargets' => [ 'reset-db4', 'insert-fixtures4' ],
            'phingVariables' => [
                'env' => 'production',
                'hostname' => 'site5.com'
            ]
        ],
        [
            'type' => 'phpbench',
            'paths' => [ '/path/ten', '/path/eleven' ],
            'blocking' => '1',
            'phingTargets' => [ 'reset-db5', 'insert-fixtures5' ],
            'phingVariables' => [
                'env' => 'staging',
                'hostname' => 'site5.com'
            ]
        ],
        [
            'type' => 'behat',
            'paths' => [ '/path/ten', '/path/eleven' ],
            'blocking' => '1',
            'useProgressFormatter' => '1',
            'phingTargets' => [ 'reset-db6', 'insert-fixtures6' ],
            'phingVariables' => [
                'env' => 'staging',
                'hostname' => 'site6.com',
            ]
        ]
    ],
    'deployment' => [
        'type' => 'tarball',
        'destinations' => [
            [
                'name' => 'my first destination',
                'url' => '<url1>',
                'pullRequest' => '1',
            ],
            [
                'name' => 'my second destination',
                'url' => '<url2>',
                'pullRequest' => '0'
            ],
            [
                'name' => 'my third destination',
                'url' => '<url3>'
            ]
        ]
    ],
    'notificationHooks' => [
        [
            'type' => 'slack',
            'url' => 'https://slack.com/hook-url1'
            'events' => [
                'createBuild' => '1',
                'buildSuccess' => '1'
            ]
        ],
        [
            'type' => 'irc',
            'url' => 'chat.freenode.net',
            'channel' => '#testchannel',                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
            'port' => '1234',
            'events' => [
                'createBuild' => '1',
                'buildFail' => '1'
            ]
        ]
    ]
]);
```

### Get the successful and in warning build list for a specific branch
```php
$builds = $service->getBuilds([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'ref' => 'refs/heads/master',
    'state' => ['complete'],
    'result' => ['success', 'warning']
]);
```

### Get the package download url of the last build
```php
$package = $service->getPackage([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'buildId' => $builds['_embedded']['builds'][0]['buildId'],
    'packageType' => 'deploy'
]);
$url = $package['url'];
```

### Download the package of the last build
```php
$package = $service->downloadPackage([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'buildId' => $builds['_embedded']['builds'][0]['buildId'],
    'packageType' => 'deploy',
    'destination' => '/path-to-destination-folder'
]);
$packagePath = $package['path'];
```
