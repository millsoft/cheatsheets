<?php

namespace Millsoft\Cheatsheets;

use Slim\Http\Request;
use Slim\Http\Response;
// Routes
$app->get('/test/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message

    $this->logger->info("Slim-Skeleton '/' route");

    $items = $this->db->run('SELECT 
*
FROM items i 
LEFT JOIN categories c ON c.id = i.`category_id` 
ORDER BY c.pos, c.categoryname, i.grp


');

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);
    return $this->view->render($response, 'index.phtml', $args);
});


//$app->get('/', \Millsoft\Cheatsheets\Management::class . ':dashboard')->setName("management.dashboard");
$app->get('/', Books::class . ':index')->setName("index");
$app->get('/cheatsheet/{cleanname}', Books::class . ':cheatsheet')->setName("cheatsheet");
