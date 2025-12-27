# CouchDB Driver for Kipchak

This is a CouchDB driver for Kipchak. It's based on the `mamluk/couchdb` package at https://1x.ax/mamluk/couchdb.

It supports multiple CouchDB connections.

#### Composer Package

`kipchak/driver-couchdb`

#### Sample Config File:

This config file should be placed in your Kipchak project's config directory, as in the starter
project at https://1x.ax/mamluk/kipchak/starter/~files/master/config/kipchak.couchdb.php.

The config file should look like this (also avalable in sample.config.php):

```
<?php

use function Kipchak\Core\env;

return [
    'enabled' => true,
    'connections' => [
        'default' => [
            'host' => env('COUCHDB_HOST', 'http://couchdb'), # No trailing slash, please.
            'port' => getenv('COUCHDB_PORT') !== false ? (int) getenv('COUCHDB_PORT') : 5984,
            'username' => env('COUCHDB_USER', 'api'),
            'password' => env('COUCHDB_PASSWORD', 'api'),
            'database' => env('COUCHDB_DATABASE', 'api_database')
        ]
    ]
];

```

## How to use it?

Install it via composer: `composer require kipchak/driver-couchdb`.

### Example Usage

```php
// Load the HTTP driver.
$couchdb = \Mamluk\Kipchak\Driver\CouchDB\CouchDB::get('default'); // default is a connection name defined in the config file.
// Use the $couchdb client as you would the mamluk/couchdb client.
```

## What is a Kipchak Driver?

Kipchak Drivers are used to connect Kipchak to various data sources or storage systems.

They provide a standardized interface for interacting with different data sources, allowing developers to
focus on building their applications rather than dealing with the complexities of each data source.

Drivers were introduced as a part of the Kipchak 2.0 release.

Drivers are basically Container Dependencies injected into Kipchak's Service Container.

## How do Kipchak Drivers work?

Some Kipchak drivers are wired into the Service Container via a config file (where required).

If applicable, you will find a sample config file in this repository as well as in the starter project at https://1x.ax/mamluk/kipchak/starter.

Each driver defines an implementation of Kipchak's Driver Interface. Where applicable, the dependency may
accept a parameter for a specific instance of the driver specified as a config property, for instance, in the case
of multiple database connections or S3 buckets.

So you may access the driver by invoking \Mamluk\Kipchak\Driver\Memcached\Memcached::get('cache'), where
`cache` is the name of one of the memcached connection pools specified in the config file.