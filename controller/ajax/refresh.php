<?php

require_once('../../class/collection.php');
require_once('../../class/status.php');
require_once('../../class/missing.php');
require_once('../../class/db.php');

$result = new stdClass();
if(isset($_REQUEST['id'])){
	$collection = new collection($_REQUEST['id']);
	$status = new status();
	$status = $status->getAll();
	if($collection->select()){
		$collection->refresh();
		$collection->update();
		$result->result = true;
		$result->data = array('title' => $collection->getTitle(), 'status' => $collection->getStatus(), 'from' => $collection->getFrom(), 'to' => $collection->getTo(), 'complete' => $collection->getComplete(), 'possession' => $collection->getPossession(), 'missing' => $collection->getMissing(), 'finish' => $collection->getFinish());
		$result->data['finish'] = ($result->data['finish'] == 1 ? 'Si' : 'No');
		if(isset($status[$result->data['status']])){
			$result->data['status'] = $status[$result->data['status']]['title'];
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