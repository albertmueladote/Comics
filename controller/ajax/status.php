<?php

require_once('../../class/collection.php');
require_once('../../class/status.php');
require_once('../../class/missing.php');
require_once('../../class/db.php');

$result = new stdClass();
$status = new status();
$rows = $status->getAll();
$result->result = true;
$result->data = $rows;
echo json_encode($result);