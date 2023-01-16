<?php 
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

unlink( '../'. $_POST['fondo']);


$sql="UPDATE cursos set posterior='' where idCurso={$_POST['id']};";
if($esclavo->query($sql)){
	echo 'ok';
}


?>