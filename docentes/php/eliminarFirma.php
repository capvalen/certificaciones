<?php 
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

unlink( '../'. $_POST['firma']);

?>