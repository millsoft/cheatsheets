<?php

namespace Millsoft\Cheatsheets;
use \Psr\Container\ContainerInterface;


class Books
{

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

    /**
     * START
     */
    public function index($request, $response, $args)
    {
        writeLog("Opened Startpage");
        $books = $this->db->run('SELECT * FROM books b ORDER BY b.name');

        $data = [
            "books" => $books
        ];

        return $this->view->render($response, 'index.phtml', $data);
    }



};