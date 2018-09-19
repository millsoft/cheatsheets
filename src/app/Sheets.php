<?php

namespace Millsoft\Cheatsheets;
use \Psr\Container\ContainerInterface;

class Sheets {

	protected $container;

	public $db     = null;
	public $logger = null;
	public $view   = null;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->db        = $container->db;
		$this->view      = $container->view;
		$this->logger    = $container->logger;
	}

	//Show the single book page with all cheatsheets
	public function cheatsheet($request, $response, $args) {

		writeLog("Opened Cheatsheet " . $args['cleanname']);
		
        $book = $this->db->row('SELECT * FROM books b WHERE b.cleanname = ?',
			$args['cleanname']);

		$items = $this->db->run('SELECT
        *
        FROM items i
        LEFT JOIN categories c ON c.id = i.`category_id`
        WHERE c.book_id = ?
        ORDER BY c.pos, c.categoryname, i.grp
        ', 
            $book['id']
        );

        //Render:
        foreach($items as &$item){
            $item['cmd_rendered'] = renderCommand($item['cmd']);
            $item['description_rendered'] = renderCommand($item['description']);
            $item['details_rendered'] = renderCommand($item['details']);
        }
		
        //Put everything in own groups:
        $cheatsheets = [];
        foreach ($items as $item) {
            $cheatsheets[$item['categoryname']][] = $item;
        }

        $vars = [
            "book" => $book,
            "cheatsheets"  => $cheatsheets
        ];


		return $this->view->render($response, 'sheets.phtml', $vars);
	}
};