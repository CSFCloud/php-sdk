# CSFCloud PHP SDK

[![Build Status](https://scrutinizer-ci.com/g/CSFCloud/php-sdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/CSFCloud/php-sdk/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/CSFCloud/php-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/CSFCloud/php-sdk/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/csfcloud/sdk.svg)](https://packagist.org/packages/csfcloud/sdk)

## Contents
* Key manager
* Container management
* Container

## Key manager
The key manager stores your server key, and OAuth keys.
```php
use CSFCloud\KeyManager;

$keymgr = new KeyManager([
    "key" => "server-key",
    "client_id" => "OAuth-client-id",
    "client_secret" => "OAuth-client-secret"
]);
```

## Container management
First you need to create a container manager:
```php
use CSFCloud\Containers\Manager;

$cntmgr = new Manager($keymgr);
```

You can use the container manager to list, create and open containers.
```php
$container = $cntmgr->NewContainer();
```

```php
$container = $cntmgr->GetContainer("container-id");
```

## Container
Get and set the container name
```php
$old_name = $container->GetContainerName();
$contaier->SetContainerName("New name");
```

Delete container:
```php
$container->Delete();
```