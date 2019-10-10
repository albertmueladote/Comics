<?php
	include('class/autoload.php');
	
    if(!isset($_SESSION['view'])){
        $_SESSION['view'] = 'table';
	}
    $session = $_SESSION['view'];

    //af_doc_add_single_css($session);
    //af_doc_add_single_js($session);

    include('controller/' . $session . '.php');   
?>