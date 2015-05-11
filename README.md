# continuousphp SDK

[![Build Status](https://status.continuousphp.com/git-hub/continuousphp/sdk?token=9800bb61-98f2-447d-a331-025f0b9af298)](https://continuousphp.com/git-hub/continuousphp/sdk)

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
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'ref' => 'refs/heads/master',
    'state' => ['complete'],
    'result' => ['success', 'warning']
]);

// Get the package download url of the last build
$package = $service->getPackage([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'buildId' => $project['_embedded']['builds'][0]['buildId'],
    'packageType' => 'deploy'
]);
$url = $package['url'];
```

// Download the package of the last build
$package = $service->downloadPackage([
    'provider' => 'git-hub',
    'repository' => 'continuousphp/sdk',
    'buildId' => $project['_embedded']['builds'][0]['buildId'],
    'packageType' => 'deploy',
    'destination' => '/tmp'
]);
$packagePath = $package['path'];
```
