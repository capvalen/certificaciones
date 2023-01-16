<?php 
include '../conectkarl.php';

$_POST = json_decode(file_get_contents('php://input'),true); 
$filas = [];
$sql="SELECT ac.*, c.curTitulo, date_format(c.curFechaGeneracion, '%d/%m/%Y') as curFechaGeneracion FROM `alumnocurso` ac
inner join cursos c on c.idCurso = ac.cursoId
where (aluCodPersonalizado = '{$_POST['texto']}'
or aluDNI = '{$_POST['texto']}'
or concat( ac.aluDNI , '-', c.curCodigo  ) = '{$_POST['texto']}')
and aluActivo = 1 and curActivo=1;";
$resultado=$cadena->query($sql);

while($row=$resultado->fetch_assoc()){
	$filas[] = $row;
}
echo json_encode($filas);
?>