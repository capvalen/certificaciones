<?php 
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="UPDATE `docentes` SET `docActivo` = '0' WHERE `docentes`.`idDocente` = {$_POST['idDocente']};";
if($cadena->query($sql)){
	echo 'ok';
}