<?php

use Psr\Container\ContainerInterface;
use Mamluk\Kipchak\Driver\CouchDB\Driver;

/**
 * @var $container ContainerInterface
 */

Driver::initialise($container);
Driver::set();
