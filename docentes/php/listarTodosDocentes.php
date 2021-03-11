<?php 

include "../../conectkarl.php";

$sql="SELECT * FROM `docentes` where docActivo=1";
$resultado=$cadena->query($sql);
$docentes = array();
while($row=$resultado->fetch_assoc()){ 
	$docentes[] = array(
		'id' => $row['idDocente'],
		'nombre' => $row['docNombres'],
		'cargo' => $row['docGrado'],
		'area' => $row['docArea'],
		'firma' => $row['docFirma'],

	);
}
echo json_encode($docentes);	
?>