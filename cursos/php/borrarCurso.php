<?php 
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="UPDATE `cursos` SET `curActivo` = b'0' WHERE `cursos`.`idCurso` = {$_POST['idCurso']};";
//echo $sql;
if($cadena->query($sql)){
	echo 'ok';
}