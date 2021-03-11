<?php
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 
//var_dump($_POST['docActual']['nombre']);

$docenteActual = $_POST['docActual'];
//echo $docenteActual['firma'];

$sql="INSERT INTO `docentes` 
(`idDocente`, `docNombres`, `docGrado`, `docArea`, `docFirma`) 
VALUES
(null, '{$docenteActual['nombre']}', '{$docenteActual['cargo']}', '{$docenteActual['area']}', '{$docenteActual['firma']}' ); ";
if($resultado=$cadena->query($sql)){ 
	echo $cadena->insert_id;
}