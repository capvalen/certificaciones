<?php 
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

//if(!isset($_GET['codigo'])) header("location: ../index.php");

include "../conectkarl.php";

if(isset($_GET['codigo'])){
	$idAlu = base64_decode($_GET['codigo']);
	$sql="SELECT a.*, c.*, nombreMesFecha(curFechaGeneracion) as fechaLetras, upper(a.aluNombre) as nombreAlumno FROM `alumnocurso` a
	inner join cursos c on c.idCurso = a.cursoId
	where a.idAlumno ={$idAlu} and aluActivo=1 and curActivo=1; ";
}
else if(isset($_GET['dni']) && isset($_GET['curso']) ){
	$dni = base64_decode($_GET['dni']); $curso = base64_decode($_GET['curso']);
	$sql="SELECT a.*, c.*, nombreMesFecha(curFechaGeneracion) as fechaLetras, upper(a.aluNombre) as nombreAlumno FROM `alumnocurso` a
	inner join cursos c on c.idCurso = a.cursoId
	where a.aluDNI ={$dni} and a.cursoId = {$curso} and aluActivo=1 and curActivo=1; ";
}
else if(isset($_GET['pers']) && isset($_GET['curso']) ){
	$pers = base64_decode($_GET['pers']); $curso = base64_decode($_GET['curso']);
	$sql="SELECT a.*, c.*, nombreMesFecha(curFechaGeneracion) as fechaLetras, upper(a.aluNombre) as nombreAlumno FROM `alumnocurso` a
	inner join cursos c on c.idCurso = a.cursoId
	where a.aluCodPersonalizado ='{$pers}' and a.cursoId = {$curso} and aluActivo=1 and curActivo=1; ";
}else{
	header("location: ../index.php");
}


//echo $sql; die();
$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();

if( $resultado->num_rows==0 ){
	header("location: ../index.php");
}else{
	$idAlu = $row['idAlumno'];
}


$sqlDocente1="SELECT c.curFirma1, d.* FROM `alumnocurso` a 
inner join cursos c on c.idCurso = a.cursoId
left join docentes d on d.idDocente = c.curFirma1
where a.idAlumno ={$idAlu}";
$resultadoDocente1=$esclavo->query($sqlDocente1);
$rowDocente1=$resultadoDocente1->fetch_assoc();

$sqlDocente2="SELECT c.curFirma2, d.* FROM `alumnocurso` a 
inner join cursos c on c.idCurso = a.cursoId
left join docentes d on d.idDocente = c.curFirma2
where a.idAlumno ={$idAlu}";
$resultadoDocente2=$conf->query($sqlDocente2);
$rowDocente2=$resultadoDocente2->fetch_assoc();

//require '../fpdf/fpdf.php';
require('../fpdf/mem_image.php');

$ruta = urlencode('https://inteslaeducation.com/certificados/certificados/index.php?codigo='.urlencode (base64_encode($idAlu)) );
$imagePath = file_get_contents("https://inteslaeducation.com/certificados/certificados/qr.php?web=" . $ruta );

$pdf = new PDF_MemImage();
$pdf->SetTitle('Certificado Intesla Education');
/* $pdf->AddFont('Times','','Times-REGULAR.php');
$pdf->AddFont('Times','BI','Times-MEDIUM.php');
$pdf->AddFont('Times','B','Times-BOLD.php'); */
$pdf->AddPage('L');

$pdf->Image( '../cursos/' . $row['curFondo'] ,0,0,297, 210); //'imgs/fondo1.png'
if($rowDocente1['docFirma']!=''){$pdf->Image('../docentes/' .  $rowDocente1['docFirma']  ,90,149,30);}
if($rowDocente2['docFirma']!=''){$pdf->Image('../docentes/' .  $rowDocente2['docFirma']  ,180,149,30);}

if( !isset($_GET['blanco']) ):

$pdf->SetFont('Times','',10);
/* 
$pdf->Cell(40,10,' ¡Hola, Mundo 1!');
$pdf->SetFont('Times','BI',16);
$pdf->Cell(80,10,' ¡Hola, Mundo 1!');
$pdf->SetFont('Times','b',16);
$pdf->Cell(120,10,' ¡Hola, Mundo 1!'); */
$pdf->SetAutoPageBreak(false);
$anchoPag = $pdf->GetPageWidth()-20;
$pdf->SetY(68);
/* $pdf->MultiCell($anchoPag, 3, utf8_decode('Otorgado a:'), 0, 'C'); */

$pdf->Ln();

$pdf->SetFont('Times','B',22);
$pdf->MultiCell($anchoPag, 15, strtoupper(utf8_decode( $row['nombreAlumno'] )), 0, 'C');

$pdf->SetFont('Times','', 18);
$pdf->SetXY(10,80);

if($row['aTipo']=='1'){
	$pdf->Cell($anchoPag, 10, utf8_decode('Quien ha culminado y aprobado satisfactoriamente el curso de:'), 0,1, 'C');
}
if($row['aTipo']=='2'){
	$pdf->Cell($anchoPag, 10, utf8_decode('Por haber participado satisfactoriamente en el seminario especializado en:'), 0,1, 'C');
}

$pdf->SetFont('Times','B', 22);
$pdf->Cell($anchoPag, 10, utf8_decode( $row['curTitulo'] ), 0,1, 'C');

$pdf->SetX(40);
$pdf->SetFont('Times','', 18);
if($row['aTipo']=='1'){
$pdf->MultiCell($anchoPag-60, 10, utf8_decode("En el grupo del {$row['curFechaIntervalo']}, obteniendo una nota de ". queNota($row['nota']) ." ({$row['nota']}), acumulando un total de " . $row['curHoras'] ." horas académicas."), 0, 'C');
}
if($row['aTipo']=='2'){
	$pdf->MultiCell($anchoPag-60, 10, utf8_decode("Desarrollado el {$row['curFechaIntervalo']}, organizado por Intesla Education, con un total de " . $row['curHoras'] ." horas académicas."), 0, 'C');
}
$pdf->Ln();

$pdf->SetXY(10,130);
$fecha = new DateTime($row['curFechaGeneracion']);
$pdf->SetFont('Times','I', 18);

$pdf->Cell($anchoPag-32, 10, utf8_decode('Lima, '. $fecha->format('d') .' de '. queMes($fecha->format('m')) .' de '. $fecha->format('Y') ), 0,1, 'R');

$pdf->SetFont('Times','BI', 12);
$pdf->MultiCell($anchoPag, 5, utf8_decode( $row['curPonente'] ), 0, 'C');


$pdf->SetFont('Times','B', 11);
$pdf->SetXY(70, 170);
$pdf->Cell(61, 5, utf8_decode( $rowDocente1['docNombres'] ) ,0,0,'C' );
$pdf->SetX(161);
$pdf->Cell(61, 5, utf8_decode( $rowDocente2['docNombres'] ) ,0,0,'C' );
$pdf->SetFont('Times','', 11);
$pdf->Ln();
$pdf->SetX(70);
$pdf->Cell(61, 5, utf8_decode( $rowDocente1['docGrado'] ),0,0,'C');
$pdf->SetX(161);
$pdf->Cell(61, 5, utf8_decode( $rowDocente2['docGrado'] ),0,0,'C');

$pdf->Ln();
$pdf->Ln();
$pdf->SetX($anchoPag/12);
/* 
$pdf->Cell(15, 4, utf8_decode('Aprobado'));
$pdf->Cell(61, 4, utf8_decode(': '. $row['curResolucion'])); */





$pdf->MemImage($imagePath,23.5,135,33);
$pdf->SetXY(15, 170);
$pdf->SetFont('Times','', 8);
$pdf->MultiCell(50, 3, utf8_decode('Verifique la validez de este certificado en: https://inteslaeducation.com/certificados'), 0, 'C');
$pdf->Ln();
$pdf->SetXY(15,178);


$pdf->SetFont('Times','B', 8);

if( $row['aluDNI'] == '' ){
	//$pdf->Cell(61, 4, utf8_decode(), 0,0, 'C'); //.'-'. $row['curCodigo']));
	$pdf->MultiCell(50, 3, utf8_decode('Código: '.$row['aluCodPersonalizado'] ),0, 'C');
}else{
	$pdf->MultiCell(50, 3, utf8_decode('Código: '. $row['aluDNI'] .'-'. $row['curCodigo'] ),0, 'C');
	//$pdf->Cell(61, 4, utf8_decode('Código: '.$row['aluDNI']) .'-'. $row['curCodigo'], 0,0, 'C' );

}

if( $row['posterior']<>'' ){
	$pdf->AddPage('L');
	$pdf->Image( '../cursos/'. $row['posterior'] , 0, 0, 297, 210);
/* $pdf->SetX(50);
$pdf->MultiCell(60,10, 'Este es un texto muy largo para que ingrese en una sola linea', 'LRTB','L',false); */

}

endif;

$pdf->Output("I",'Certificado Intesla Education.pdf', true);

function queNota($nota){
	switch ($nota) {
		case 0: case '0': case '00': return 'cero'; break;
		case 1: case '1': case '01':return 'cero uno'; break;
		case 2: case '2': case '02':return 'cero dos'; break;
		case 3: case '3': case '03':return 'cero tres'; break;
		case 4: case '4': case '04':return 'cero cuatro'; break;
		case 5: case '5': case '05':return 'cero cinco'; break;
		case 6: case '6': case '06':return 'cero seis'; break;
		case 7: case '7': case '07':return 'cero siete'; break;
		case 8: case '8': case '08':return 'cero ocho'; break;
		case 9: case '9': case '09':return 'cero nueve'; break;
		case 10: case '10': case '10':return 'diez'; break;
		case 11: case '11': case '11':return 'once'; break;
		case 12: case '12': case '12':return 'doce'; break;
		case 13: case '13': case '13':return 'trece'; break;
		case 14: case '14': case '14':return 'catorce'; break;
		case 15: case '15': case '15':return 'quince'; break;
		case 16: case '16': case '16':return 'dieciséis'; break;
		case 17: case '17': case '17':return 'diecisiete'; break;
		case 18: case '18': case '18':return 'dieciocho'; break;
		case 19: case '19': case '19':return 'diecinueve'; break;
		case 20: case '20': case '20':return 'veinte'; break;
		default:
			# code...
			break;
	}
}
function queMes($mes){
	switch ($mes) {
		case 1: case '1': case '01': return 'enero'; break;
		case 2: case '2': case '02':return 'febrero'; break;
		case 3: case '3': case '03':return 'marzo'; break;
		case 4: case '4': case '04':return 'abril'; break;
		case 5: case '5': case '05':return 'mayo'; break;
		case 6: case '6': case '06':return 'junio'; break;
		case 7: case '7': case '07':return 'julio'; break;
		case 8: case '8': case '08':return 'agosto'; break;
		case 9: case '9': case '09':return 'septiembre'; break;
		case 10: case '10': case '10':return 'octubre'; break;
		case 11: case '11': case '11':return 'noviembre'; break;
		case 12: case '12': case '12':return 'diciembre'; break;
		default: # code...
		break;
	}
}
?>