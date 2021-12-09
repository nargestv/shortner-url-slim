<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Repository\UserRepository;

class UserController
{
    private $view;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $json = $request->getBody();
      $data = json_decode($json, true); 
    
      $this->repository->create($data);
      
      return $response;
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $json = $request->getBody();
      $data = json_decode($json, true); 

      return $this->repository->login($data);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $json = $request->getBody();
      $data = json_decode($json, true); 
      $personId = $request->params('id');

      return $this->repository->update($personId, $data);
    }
}