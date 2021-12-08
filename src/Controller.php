<?php
namespace App\src;

class Controller {
    protected $container;
    public function __construct($c) {
        $this->container = $c;
    }

    public function __get($property) {
        if($this->container->has($property)) {
            return $this->container->get($property);
        }
        return $this->{$property};
    }
}