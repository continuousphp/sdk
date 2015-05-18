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
