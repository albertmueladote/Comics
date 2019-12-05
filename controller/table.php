<?php

$b = new book();
$books = $b->getAll();
var_dump($books);

include("view/header.php");
include("view/$session.php");
include("view/footer.php");
