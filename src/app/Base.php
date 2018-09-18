<?php

namespace Millsoft\Cheatsheets;

use \Psr\Container\ContainerInterface;

class Base{

    protected $container;

    public $db = null;
    public $logger = null;
    public $view = null;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->db= $container->db;
        $this->view= $container->view;
        $this->logger= $container->logger;
    }
}