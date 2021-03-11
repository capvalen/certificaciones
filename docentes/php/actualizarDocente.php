<?php
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 
//var_dump($_POST['docActual']['nombre']);

$docenteActual = $_POST['docActual'];
//echo $docenteActual['firma'];

$sql="UPDATE `docentes` SET `docNombres`='{$docenteActual['nombre']}',
`docGrado`='{$docenteActual['cargo']}',
`docArea`='{$docenteActual['area']}',
`docFirma`='{$docenteActual['firma']}' 
WHERE idDocente= {$docenteActual['id']};";
if($resultado=$cadena->query($sql)){ 
	echo 'ok';
}