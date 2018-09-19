<?php

//global helper functions



function writelog($txt, $extra = []){
	//TODO: Needs implementation
	//use monolog
    //$this->logger->info("Startpage");
	
}

function printr($data, $die = true, $obclean = false) {
	if ($obclean) {
		ob_clean();
	}

	$bt     = debug_backtrace();
	$caller = array_shift($bt);

	echo "<pre>";
	echo "<b>{$caller['file']} <span style='color:red'>(Line: {$caller['line']})</span></b><br/>\n";
	print_r($data);
	echo "\n</pre>";

	if ($die) {
		die();
	}
}


//Render a command for html
//Example:  {key_A} will become <span class='render key'>A</span>
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