<?php
namespace App;

use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Container\NotFoundExceptionInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

class authorization {
      public function __invoke($request, $handler): Response {
                      $routeContext = RouteContext::fromRequest($request);
                      $route = $routeContext->getRoute();
                      if(empty($route)) { throw new NotFoundExceptionInterface($request, $response); }
                      $routeName = $route->getName();
                      $publicRoutesArray = array('f401');
                      if(empty($_SESSION['user']) && (in_array($routeName, $publicRoutesArray))) {
                         $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                         $url = $routeParser->urlFor('login');
                         $responseFactory = new \Nyholm\Psr7\Factory\Psr17Factory();
                         $response = $responseFactory->createResponse(200);
                         return $response->withHeader('Location', $url)->withStatus(302);
                      } else { $response = $handler->handle($request); }
             return $response;
      }
}