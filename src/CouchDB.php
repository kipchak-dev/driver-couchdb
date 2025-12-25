<?php

namespace Kipchak\Driver\CouchDB;

use Illuminate\Http\Client\Factory;
use Kipchak\Core\Interfaces;
use Psr\Container\ContainerInterface;
use Mamluk\Couch\Database\Client;
use Kipchak\Driver\Config\Config;
use Kipchak\Driver\Logger\Logger;

class CouchDB implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container, ?string $configFileName = null): void
    {
        self::$container = $container;
        self::set($configFileName);
    }

    public static function set(?string $configFileName = null): void
    {
        $configFileName = $configFileName ?? 'kipchak.couchdb';
        if (isset(Config::get($configFileName)['connections']) && Config::get($configFileName)['enabled']) {
            $connections = Config::get($configFileName)['connections'];
            $logger = Logger::get();
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