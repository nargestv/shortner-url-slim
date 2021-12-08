<?php
/**
 * Slim 4.x does not ship with a container library. It supports all PSR-11 implementations such as PHP-DI
 * To install PHP-DI `composer require php-di/php-di`
 */

use Slim\Factory\AppFactory;

$container = new \DI\Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$container = $app->getContainer();
$container['some.controller'] = function ($container) {
    /**
     * @var ContainerInterface $container
     */
    $logger = $container->get('logger');

    return new Controller($logger);
};