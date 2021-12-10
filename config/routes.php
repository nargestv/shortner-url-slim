<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteContext;
use Slim\Exception\NotFoundException;
use Slim\Exception\HttpNotFoundException;
use Selective\BasePath\BasePathDetector;
use Selective\BasePath\BasePathMiddleware;
use App\authorization;

$container->set('UserController', function (ContainerInterface $container) {
    // retrieve the 'view' from the container
    $view = $container->get('view');
    
    return new \App\Controllers\UserController($view);
});

return function (App $app) {
    // $routingMiddleware = new Slim\Middleware\RoutingMiddleware(
    //         $app->getRouteResolver(),
    //         $app->getRouteCollector()->getRouteParser()
    // );
    // $app->add(new authorization());
    // $app->addBodyParsingMiddleware();
    // $app->addMiddleware($routingMiddleware);
    // $app->add(new BasePathMiddleware($app));
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/register', \App\Controllers\UserController::class . ':register');
        $group->post('/login', \App\Controllers\UserController::class . ':login');

          // API group    
        $group->get('/{url}', \App\Controllers\UrlController::class . ':getRoute');
        $group->post('/create', \App\Controllers\UrlController::class . ':generateUrl');
        $group->post('/edit/{url}', \App\Controllers\UrlController::class . ':updateUrl');
        $group->post('/delete/{url}', \App\Controllers\UrlController::class . ':deleteUrl');
     });
    

    $app->get('/', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $response->getBody()->write('Hello, World!');

        return $response;
    });


  

};

