<?php 
include '../conectkarl.php';

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT * FROM `alumnocurso` where aluDNI ='{$_POST['dni']}' and cursoId={$_POST['idCurso']}";
$resultado=$cadena->query($sql);
$cantFilas= $resultado->num_rows;
if($cantFilas ==0){
	echo 'No existe certificado';
}
else if($cantFilas==1){
	$row=$resultado->fetch_assoc();
	$idAlum = base64_encode($row['idAlumno']);
	echo $_SERVER['SERVER_NAME']."/certificaciones/certificados/index.php?codigo=".urlencode($idAlum);

}
else if($cantFilas>1){
	echo "Existe duplicados";
}



	
?>