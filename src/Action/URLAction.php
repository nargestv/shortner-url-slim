<?php

namespace App\Action;

use App\Controllers\UrlCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class URLAction
{
    private $userCreator;

    public function __construct(UrlCreator $urlCreator)
    {
        $this->urlCreator = $urlCreator;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();
        

        $getPath = $request->getUri()->getPath();
      
        // Invoke the Domain with inputs and retain the result
        $result = $this->urlCreator->fetchUrl($getPath);

        // Transform the result into the JSON representation
        $result = [
            'result' => $result
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}