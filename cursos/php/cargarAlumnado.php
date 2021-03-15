<?php 
include "../../conectkarl.php";

//var_dump($_POST['alumnado']);
$alumnos = $_POST['alumnado'];
$sql='';

for ($i=0; $i<count($alumnos); $i++ ) {
	//var_dump($alumnos[$i]['dni']);
	$sql.="INSERT INTO `alumnocurso`(`idAlumno`, `aluNombre`, `aluDNI`, `cursoId`) VALUES (null, '{$alumnos[$i]['nombre']}', '{$alumnos[$i]['dni']}', '{$_POST['idCurso']}'); ";
}
//echo $sql;

if($cadena->multi_query($sql)){
	echo 'ok';
}

?>