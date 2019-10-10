<?php
spl_autoload_register(function ($class) {
	$path =  'class/' . $class . '.php';
	if(file_exists($path)){
		include($path);
	}
});
?>