<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Repository\UrlRepository;

class UserController
{
    private $view;

    public function __construct(UrlRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

      $this->repository->create();
      
      return $response;
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $this->repository->create();
    }

}