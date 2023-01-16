<?php 

include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="UPDATE `alumnocurso` SET `aluDNI` = '{$_POST['dni']}' WHERE `alumnocurso`.`idAlumno` = {$_POST['id']}; ";

if($cadena->query($sql)){
	echo 'ok';
}


?>