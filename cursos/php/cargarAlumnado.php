<?php 
include "../../conectkarl.php";

//var_dump($_POST); die();
$alumnos = $_POST['alumnado'];
$sql='';

for ($i=0; $i<count($alumnos); $i++ ) {
	if( $alumnos[$i]['nombre'] !='' ){
		if($_POST['aTipo']=='1'){ $texto = 'Asistente en el Curso Especialización'; }
		else if($_POST['aTipo']=='2'){ $texto = 'Asistente al seminario'; }
		else{$texto='';}
		//var_dump($alumnos[$i]['dni']);
		$sql.="INSERT INTO `alumnocurso`(`idAlumno`, `aluNombre`, `aluDNI`, `cursoId`,
		nota, correo, aluCodPersonalizado, aTipo, aluAsistente
		) VALUES (null, '{$alumnos[$i]['nombre']}', '{$alumnos[$i]['dni']}', '{$_POST['idCurso']}',
		'{$alumnos[$i]['nota']}', '{$alumnos[$i]['correo']}', '{$alumnos[$i]['codPers']}', '{$_POST['aTipo']}', '{$texto}'
		); ";
	
	
		if( $alumnos[$i]['correo'] <>'' ){
			
			$_POST['nombre'] = $alumnos[$i]['nombre'];
	//		$_POST['curso'] = $_POST['nombreCurso'];
			$_POST['cliente'] = $alumnos[$i]['correo'];
			$_POST['codigo'] = $alumnos[$i]['dni'];
			$_POST['pers'] = $alumnos[$i]['codPers'];
			ob_start();
			require('./mail.php');
			$data = ob_get_clean();
	//		printf($data);
		}
	}
}
//echo $sql; die();



if($cadena->multi_query($sql)){
	echo 'ok';
}

?>