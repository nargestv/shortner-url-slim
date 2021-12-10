<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Repository\UrlRepository;

class UrlController
{
    private $view;

    public function __construct(UrlRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function getRoute(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      return $this->repository->getByID($args);
      
    }

    public function generateUrl(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $data = $request->getParsedBody();

      return $this->repository->create($data);
    }

    public function updateUrl(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $json = $request->getBody();
      $data = json_decode($json, true); 
      $id = $request->params('id');

      return $this->repository->update($id, $data);
    }

    public function deleteUrl(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $json = $request->getBody();
      $data = json_decode($json, true); 
      $id = $request->params('id');

      return $this->repository->delete($id, $data);
    }
}