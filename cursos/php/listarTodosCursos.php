<?php 

include "../../conectkarl.php";

$sql="SELECT c.*, d.* FROM `cursos` c
left join docentes d on d.idDocente = curFirma1
left join docentes do on do.idDocente = curFirma2
where curActivo=1 order by curTitulo";
$resultado=$cadena->query($sql);
$docentes = array();
while($row=$resultado->fetch_assoc()){ 
	$docentes[] = array(
		'id' => $row['idCurso'],
		'titulo' => $row['curTitulo'],
		'subTitulo' => $row['curSubtitulo'],
		'ponente' => $row['curPonente'],
		'fechaIntervalo' => $row['curFechaIntervalo'],
		'fechaGeneracion' => $row['curFechaGeneracion'],
		'horas' => $row['curHoras'],
		'firma1' => $row['curFirma1'],
		'firma2' => $row['curFirma2'],
		'resolucion' => $row['curResolucion'],
		'registro' => $row['curRegistros'],
		'tomo' => $row['curTomo'],
		'codigo' => $row['curCodigo'],
		'fondo' => $row['curFondo']

	);
}
echo json_encode($docentes);	
?>