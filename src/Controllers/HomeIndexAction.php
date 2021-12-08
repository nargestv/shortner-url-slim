<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\src\Controller;
use Psr\Container\ContainerInterface;

class HomeIndexAction extends Controller {
{
    protected $container;

    public function home(Request $request, Response $response) {
        return $this->db;
    }
}