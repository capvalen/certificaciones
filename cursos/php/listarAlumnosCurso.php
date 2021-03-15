<?php 

include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT * FROM `alumnocurso`
where aluActivo=1 and cursoId='{$_POST['idCurso']}'; ";
$resultado=$cadena->query($sql);
$fila = array();
while($row=$resultado->fetch_assoc()){ 
	$fila[] = $row;
}

echo json_encode($fila);
?>