<?php

require_once('../../class/collection.php');
require_once('../../class/status.php');
require_once('../../class/missing.php');
require_once('../../class/db.php');

$result = new stdClass();
if(isset($_REQUEST['id']) && isset($_REQUEST['value'])){
	
	$id = (int)end(explode('_', $_REQUEST['id']));
	$value = (int)$_REQUEST['value'];
	
	$collection = new collection($id);

	if($collection->select()){

		$collection->refresh();

		if($value == 0 || $value == 1){

			$result->result = true;
			$result->id = $id;

			if($collection->getMissing() > 0){
				$collection->setFinish(0);
				$result->label = 'No';
				if($value){
					$result->alert = 'No puedes completar una colección si faltan cómics';
				}
			}else{
				if($value){
					$result->label = 'Si';
					$collection->setFinish(1);
				}else{
					$result->label = 'No';
					$collection->setFinish(0);
				}
			}
			$collection->update();

		}else{
			$result->result = false;
			$result->err = 'ERR_PARAM_INVALID';
		}
	}else{
		$result->result = false;
		$result->err = 'ERR_ITEM_NOT_FOUND';
	}
}else{
	$result->result = false;
	$result->err = 'ERR_PARAM_NOT_FOUND';
}

echo json_encode($result);
?>