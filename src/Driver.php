<?php

namespace Kipchak\Driver\CouchDB;

use Illuminate\Http\Client\Factory;
use Kipchak\Core\Interfaces;
use Psr\Container\ContainerInterface;
use Mamluk\Couch\Database\Client;
use Kipchak\Driver\Config\Driver as Config;
use Kipchak\Driver\Logger\Driver as Logger;

class Driver implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container): void
    {
        self::$container = $container;
        self::set();
    }

    public static function set(): void
    {
        if (isset(Config::get('kipchak.couchdb')['connections']) && Config::get('kipchak.couchdb')['enabled']) {
            $connections = Config::get('kipchak.couchdb')['connections'];
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