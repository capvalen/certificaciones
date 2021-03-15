<?php 
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="UPDATE `cursos` SET 
`curFondo`='{$_POST['fondo']}',
`curCopia`= b'1'
where idCurso = {$_POST['id']}; ";
//echo $sql;
if($resultado=$cadena->query($sql)){ 
	echo 'ok';
}

 ?>