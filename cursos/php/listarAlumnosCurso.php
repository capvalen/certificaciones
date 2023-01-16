<?php 

include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT a.*, c.curTitulo FROM `alumnocurso` a inner join cursos c
on c.idCurso = a.cursoId
where aluActivo=1 and a.cursoId='{$_POST['idCurso']}'; ";
$resultado=$cadena->query($sql);
$fila = array();
while($row=$resultado->fetch_assoc()){ 
	$fila[] = $row;
}

echo json_encode($fila);
?>