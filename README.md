# continuousphp SDK

[![Build Status](https://status.continuousphp.com/git-hub/continuousphp/phing-tasks?token=bb175a86-acb5-4f62-92b5-86d5900b6971)](https://continuousphp.com/git-hub/continuousphp/phing-tasks)

continuousphp SDK enables PHP developers to use [continuousphp](https://continuousphp.com/) in their PHP code.

## Installation

Install this package through [Composer](https://getcomposer.org/) by adding this package in the require section

```json
"require": {
    "continuousphp/sdk": "~0.1"
}
```

## Quick Example

### Get a package url
```php
<?php
require 'vendor/autoload.php';

$service = Continuous\Sdk\Service::factory(['token' => 'cc2efee7-be03-4611-923e-065bc3dd3326']);

// Get the build list for a specific branch
$project = $service->getBuilds([
    'projectId' => 'git-hub/continuousphp/sdk',
    'ref' => '/refs/heads/master',
    'state' => ['complete'],
    'result' => ['success', 'warning']
]);

// Get the package download url of the last build
$package = $service->getPackage([
    'projectId' => 'git-hub/continuousphp/sdk',
    'buildId' => $project['_embedded']['builds'][0]['buildId'],
    'packageType' => 'deploy'
]);
$url = $package['url’];
```
