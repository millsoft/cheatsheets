<?php

namespace Millsoft\Cheatsheets;
use \Psr\Container\ContainerInterface;
use Slim\Exception\NotFoundException;

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

        $cheatsheets = $this->getCheatSheets($book['id']);

        $vars = [
            "book" => $book,
            "cheatsheets"  => $cheatsheets
        ];

		return $this->view->render($response, 'sheets.phtml', $vars);
	}

    /**
     * Get cheatsheets for a book
     * @param $book_id
     * @return array
     */
	public function getCheatSheets($book_id){

	    if(!is_numeric($book_id)){
	        //Get book_id by cleanname
            $book = $this->db->row('SELECT * FROM books b WHERE b.cleanname = ?', $book_id);
            if(empty($book)){
                throw new NotFoundException("Book not found");
            }
            $book_id = $book['id'];
        }

        $items = $this->db->run('SELECT
        *
        FROM items i
        LEFT JOIN categories c ON c.id = i.`category_id`
        WHERE c.book_id = ?
        ORDER BY c.pos, c.categoryname, i.grp
        ',
            $book_id
        );

        //Render:
        foreach($items as &$item){
            $item['cmd_rendered'] = renderCommand($item['cmd']);
            $item['description_rendered'] = renderCommand($item['description']);
            $item['details_rendered'] = renderCommand($item['details']);
            $item['visible_command'] = 1;
            $item['visible_description'] = 1;
        }

        //Put everything in own groups:
        $cheatsheets = [];
        foreach ($items as $item) {
            $cheatsheets[$item['categoryname']][] = $item;
        }
        return $cheatsheets;
    }

	//print Sheets as json
	public function jCheatSheet($request, $response, $args){
        $cheatsheets = $this->getCheatSheets($args['book']);

        $resp = [
            'cheatsheets' => $cheatsheets
        ];

        return $response->withJson($resp);


    }
};