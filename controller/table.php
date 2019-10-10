<?php
$collection = new collection();
$collections = $collection->getAll();


include("view/header.php");
include("view/$session.php");
include("view/footer.php");
