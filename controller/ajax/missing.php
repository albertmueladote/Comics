<?php

require_once('../../class/collection.php');
require_once('../../class/status.php');
require_once('../../class/missing.php');
require_once('../../class/db.php');

$result = new stdClass();
if(isset($_REQUEST['id']) && isset($_REQUEST['value'])){
	$id = (int)end(explode('_', $_REQUEST['id']));
	$numbers = explode(' ', trim($_REQUEST['value']));
	if(sizeof($numbers) == 1){
		$missing = new missing($id, (int)$numbers[0]);
		if($missing->select()){
			$result->result = false;
			$result->err = 'ERR_WRONG_PARAMS';
			$result->alert = 'Este número ya está incluido.';
		}else{
			$missing->insert();
			$collection = new collection($id);
			if($collection->select()){
				$collection->refresh();
				$collection->update();
			}else{
				$result->result = false;
				$result->err = 'ERR_UPDATE';
			}
			$result->result = true;
			$result->id = $id;
		}
	}elseif(sizeof($numbers) == 2){
		$collection = new collection($id);
		if($collection->select()){
			$result->no = 0;
			$result->yes = 0;
			for ($i=min($numbers[0], $numbers[1]); $i <= max($numbers[0], $numbers[1]); $i++) {
				$missing = new missing($id, (int)$i);
				if($missing->select()){
					$result->no++;
				}else{
					$missing->insert();
					$result->yes++;		
				}
			}
			$result->result = true;
			$result->id = $id;
			if($result->yes > 0){
				$collection->refresh();
				$collection->update();
			}
		}else{
				$result->result = false;
				$result->err = 'ERR_ITEM_NOT_FOUND';
		}
	}else{
		$result->result = false;
		$result->err = 'ERR_WRONG_PARAMS';
		$result->alert = 'Introduce un número para ingresar uno o dos números separados por un espacio para ingresar un rango.';
	}
}else{
	$result->result = false;
	$result->err = 'ERR_PARAMS_NOT_FOUND';
}

echo json_encode($result);
