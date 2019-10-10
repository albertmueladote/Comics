<?php


function prepare()
{
	$shortcodes = array('%t', '%s', '%n', '%c');
	$params = array();
	if(func_num_args() > 1){
		$string = func_get_args()[0];
		for ($i=1; $i < sizeof(func_get_args()); $i++) { 
			array_push($params, func_get_args()[$i]);
		}
	}

	$pointer_params = 0;
	$loop = true;

	while($loop){
		$pointer_string = strlen($string);

		foreach ($shortcodes as $s) {
			$pos = strpos($string, $s);
			if($pos){
				if($pos < $pointer_string){
					$pointer_string = $pos;
					$next = $s;				
				}
			}
		}
		
		if(is_null($next)){
			$loop = false;
		}else{
			if(isset($params[$pointer_params])){
				switch($next){
					case '%s':
						$params[$pointer_params] = "'" . $params[$pointer_params] . "'";
						break;
					case '%n':
						$params[$pointer_params] = intval($params[$pointer_params]);
						break;
					case '%c':
						$params[$pointer_params] = "'%" . $params[$pointer_params] . "%'";
						break;
					default:
						break;
				}
				$string = substr_replace($string, $params[$pointer_params], $pointer_string, 2);

				$pointer_params++;
			}	
		}
		$next = null;
	}

	return $string;
}
?>