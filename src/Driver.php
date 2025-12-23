<?php

namespace Mamluk\Kipchak\Driver\CouchDB;

use Illuminate\Http\Client\Factory;
use Mamluk\Kipchak\Interfaces;
use Psr\Container\ContainerInterface;
use Mamluk\Couch\Database\Client;
use Mamluk\Kipchak\Driver\Config\Driver as Config;

class Driver implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function set(): void
    {
        if (isset(Config::get('kipchak.couchdb')['connections']) && Config::get('kipchak.couchdb')['enabled']) {
            $connections = Config::get('kipchak.couchdb')['connections'];
            $logger = self::$container->get('driver.logger');
            $httpClient = new Factory();
            foreach($connections as $connectionName => $connectionDetails) {
                self::$container->set('drivers.database.couchdb.' . $connectionName, function (ContainerInterface $c) use ($connectionDetails, $httpClient, $logger): Client {
                    return new Client($connectionDetails['username'], $connectionDetails['password'],
                        $connectionDetails['database'], $connectionDetails['host'], $logger,
                        $httpClient, $connectionDetails['port']);
                });
            }
        }
    }

    public static function get(?string $configKey = null): Client
    {
        return self::$container->get('drivers.database.couchdb.' . $configKey ?? 'default');
    }

}