<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Container\ContainerInterface;

$container->set('UserController', function (ContainerInterface $container) {
    // retrieve the 'view' from the container
    $view = $container->get('view');
    
    return new \App\Controllers\UserController($view);
});
return function (App $app) {
    $app->get('/', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $response->getBody()->write('Hello, World!');

        return $response;
    });


    // API group
  
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/register', \App\Controllers\UserController::class . ':register');
        $group->post('/login', \App\Controllers\UserController::class . ':login');

     });

    $app->get('/{url}', \App\Action\URLAction::class );
};

