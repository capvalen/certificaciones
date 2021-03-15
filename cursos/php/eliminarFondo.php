<?php 
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT curCopia FROM `cursos` where idCurso={$_POST['id']}; ";
$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();
if($row['curCopia']=='0'){
	unlink( '../'. $_POST['fondo']);
}

$sql="UPDATE cursos set curCopia=b'0', curFondo='' where idCurso={$_POST['id']};";
if($esclavo->query($sql)){
	echo 'ok';
}



?>