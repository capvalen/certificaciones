<?php
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 
//var_dump($_POST['docActual']['nombre']);

$cursoActual = $_POST['curActual'];
//echo $cursoActual['firma'];

$sql="INSERT INTO `cursos`(`idCurso`, `curTitulo`, `curSubtitulo`, `curPonente`, `curFechaIntervalo`, `curFechaGeneracion`, `curHoras`, `curFirma1`, `curFirma2`, `curResolucion`, `curRegistros`, `curTomo`, `curCodigo`, `curFondo`)
VALUES
(null, '{$cursoActual['titulo']}', '{$cursoActual['subTitulo']}', '{$cursoActual['ponente']}', '{$cursoActual['fechaIntervalo']}', '{$cursoActual['fechaGeneracion']}', '{$cursoActual['horas']}', '{$cursoActual['firma1']}', '{$cursoActual['firma2']}', '{$cursoActual['resolucion']}', '{$cursoActual['registro']}', '{$cursoActual['tomo']}', '{$cursoActual['codigo']}', '{$cursoActual['fondo']}' ); ";
//echo $sql;
if($resultado=$cadena->query($sql)){ 
	echo $cadena->insert_id;
}else{
	echo $cadena->error;
}