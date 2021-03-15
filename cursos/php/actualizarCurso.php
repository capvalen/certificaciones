<?php
include "../../conectkarl.php";

$_POST = json_decode(file_get_contents('php://input'),true); 
//var_dump($_POST['curActual']['nombre']);

$cursoActual = $_POST['curActual'];
//echo $cursoActual['firma'];

$sql="UPDATE `cursos` SET 
`curTitulo`='{$cursoActual['titulo']}',
`curSubtitulo`='{$cursoActual['subTitulo']}',
`curPonente`='{$cursoActual['ponente']}',
`curFechaIntervalo`='{$cursoActual['fechaIntervalo']}',
`curFechaGeneracion`='{$cursoActual['fechaGeneracion']}',
`curHoras`='{$cursoActual['horas']}',
`curFirma1`='{$cursoActual['firma1']}',
`curFirma2`='{$cursoActual['firma2']}',
`curResolucion`='{$cursoActual['resolucion']}',
`curRegistros`='{$cursoActual['registro']}',
`curTomo`='{$cursoActual['tomo']}',
`curCodigo`='{$cursoActual['codigo']}',
`curFondo`='{$cursoActual['fondo']}'
where idCurso = {$cursoActual['id']}; ";
//echo $sql;
if($resultado=$cadena->query($sql)){ 
	echo 'ok';
}