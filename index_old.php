<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}


require_once(__DIR__ . "/vendor/autoload.php");

$config_file = __DIR__ . "/src/config.json";
if(!file_exists($config_file)){
	die($config_file . " is missing.");
}

$config = json_decode(file_get_contents($config_file));

$db = \ParagonIE\EasyDB\Factory::create(
    "mysql:host={$config->database->server};dbname={$config->database->database}",
    $config->database->username,
    $config->database->password
);


//Replace some stuff in they command with html wrapper
function renderCommand($cmd){
	$re = '/(\{(?P<what>key|example)_)(?P<key>.+?)\}/m';

	$repl = preg_replace_callback($re, function($found){
		$key = $found['key'];
		$what = $found['what'];
		$title = '';

		if($what == 'example'){
			$title = 'Example';
		}

		if($key == 'cursor'){
			$key = 'Cursor Keys';
			$title = 'Cursor keys or hjkl';
		}

		return "<span title='{$title}' class='render {$what}'>{$key}</span>";
	}, $cmd);

	return $repl;
}

//Get all data from DB
$items = $db->run('SELECT 
*
FROM items i 
LEFT JOIN categories c ON c.id = i.`category_id` 
ORDER BY c.pos, c.categoryname, i.grp


');


//Render data:
$data = [];
foreach($items as $item){
	$data[$item['categoryname']][] = $item;
}


?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>VIM Cheatsheet</title>
	<link rel="stylesheet" href="css/styles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	
<h1>VIM Cheatsheet</h1>

<div class="container">

	<?php foreach($data as $category => $rows): ?>
		
	<div class="group">
		<h2><?=$category?></h2>
		<?php foreach($rows as $row): ?>

	<div class="item">
		<div class="cmd" style="background-color:<?=$row['bgcolor']?>">
			<?=renderCommand($row['cmd'])?>
		</div>
		<div class="metainfo">
			<div class="description">
				<?=renderCommand($row['description'])?>.
			</div>
			
			<div class="details">
				<?=renderCommand($row['details'])?>
			</div>
		</div>

	</div>
		<?php endforeach?>
</div>

</div>
	<?php endforeach?>

</body>
</html>
