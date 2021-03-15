<?php 
if(!isset($_GET['codigo'])) header("location: ../index.php");

include "../conectkarl.php";

$idAlu = base64_decode($_GET['codigo']);

$sql="SELECT a.*, c.*, nombreMesFecha(curFechaGeneracion) as fechaLetras FROM `alumnocurso` a
inner join cursos c on c.idCurso = a.cursoId
where a.idAlumno ={$idAlu} and aluActivo=1 and curActivo=1; ";
//echo $sql;
$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();

if( $resultado->num_rows==0 ){
	header("location: ../index.php");
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

$ruta = urlencode('https://inaprof.com/certificaciones/certificados/index.php?codigo='.urlencode (base64_encode($idAlu)) );
$imagePath = file_get_contents("http://localhost/certificaciones/certificados/qr.php?web=" . $ruta );

$pdf = new PDF_MemImage();
$pdf->SetTitle('Certificado INAPROF');
$pdf->AddFont('Dubai','','DUBAI-REGULAR.php');
$pdf->AddFont('Dubai','BI','DUBAI-MEDIUM.php');
$pdf->AddFont('Dubai','B','DUBAI-BOLD.php');
$pdf->AddPage('L');

$pdf->Image( '../cursos/' . $row['curFondo'] ,0,0,297, 210); //'imgs/fondo1.png'
if($rowDocente1['docFirma']!=''){$pdf->Image('../docentes/' .  $rowDocente1['docFirma']  ,80,139,30);}
if($rowDocente2['docFirma']!=''){$pdf->Image('../docentes/' .  $rowDocente2['docFirma']  ,170,139,30);}

if( !isset($_GET['blanco']) ):

$pdf->SetFont('Dubai','',10);
/* 
$pdf->Cell(40,10,' ¡Hola, Mundo 1!');
$pdf->SetFont('Dubai','BI',16);
$pdf->Cell(80,10,' ¡Hola, Mundo 1!');
$pdf->SetFont('Dubai','b',16);
$pdf->Cell(120,10,' ¡Hola, Mundo 1!'); */
$pdf->SetAutoPageBreak(false);
$anchoPag = $pdf->GetPageWidth()-20;
$pdf->SetY(65);
$pdf->MultiCell($anchoPag, 3, utf8_decode('Otorgado a:'), 0, 'C');

$pdf->Ln();

$pdf->SetFont('Dubai','B',14.5);
$pdf->MultiCell($anchoPag, 5, utf8_decode( $row['aluNombre'] ), 0, 'C');
$pdf->Ln();
$pdf->SetFont('Dubai','', 11);
$pdf->SetX(75);
$pdf->Cell(54, 5, utf8_decode('Por su participación en calidad de '));
$pdf->SetFont('Dubai','B', 11);
$pdf->Cell(61, 5, utf8_decode( $row['aluAsistente'] ));
$pdf->SetFont('Dubai','', 11);
$pdf->Cell(100, 5, utf8_decode(', denominado:'));
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Dubai','B', 24);
$pdf->MultiCell($anchoPag, 5, utf8_decode( $row['curTitulo'] ), 0, 'C');
$pdf->Ln();
$pdf->SetFont('Dubai','BI', 14);
$pdf->MultiCell($anchoPag, 5, utf8_decode($row['curSubtitulo']), 0, 'C');
$pdf->Ln();
$pdf->SetFont('Dubai','', 12);
$pdf->SetX(30);
$pdf->MultiCell($anchoPag-60, 5, utf8_decode("Desarrolla del {$row['curFechaIntervalo']} del presente año, con una duración de {$row['curHoras']} horas académicas, seminario a cargo del docente:"), 0, 'L');
$pdf->Ln();
$pdf->SetFont('Dubai','BI', 12);
$pdf->MultiCell($anchoPag, 5, utf8_decode( $row['curPonente'] ), 0, 'C');
$pdf->SetFont('Dubai','', 10);
$pdf->MultiCell($anchoPag/1.20, 5, utf8_decode("Dado y firmado en {$row['fechaLetras']}"), 0, 'R');

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Dubai','B', 11);
$pdf->SetX(60);
$pdf->Cell(61, 5, utf8_decode( $rowDocente1['docNombres'] ) ,0,0,'C' );
$pdf->SetX(151);
$pdf->Cell(61, 5, utf8_decode( $rowDocente2['docNombres'] ) ,0,0,'C' );
$pdf->SetFont('Dubai','', 11);
$pdf->Ln();
$pdf->SetX(60);
$pdf->Cell(61, 5, utf8_decode( $rowDocente1['docGrado'] ),0,0,'C');
$pdf->SetX(151);
$pdf->Cell(61, 5, utf8_decode( $rowDocente2['docGrado'] ),0,0,'C');
$pdf->Ln();
$pdf->SetX(60);
$pdf->Cell(61, 5, utf8_decode( $rowDocente1['docArea'] ) ,0,0,'C');
$pdf->SetX(151);
$pdf->Cell(61, 5, utf8_decode( $rowDocente2['docArea'] ),0,0,'C');

$pdf->Ln();
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->SetFont('Dubai','B', 8);
$pdf->Cell(15, 4, utf8_decode('Aprobado'));
$pdf->Cell(61, 4, utf8_decode(': '. $row['curResolucion']));
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->Cell(15, 4, utf8_decode('Registro'));
$pdf->Cell(61, 4, utf8_decode(': '. $row['curRegistros']));
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->Cell(15, 4, utf8_decode('Tomo'));
$pdf->Cell(61, 4, utf8_decode(': '.$row['curTomo'] ));
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->Cell(15, 4, utf8_decode('Código'));
$pdf->Cell(61, 4, utf8_decode(': '.$row['aluCodPersonalizado'].' - '. $row['curCodigo']));


$pdf->MemImage($imagePath,20,147,27);


endif;

$pdf->Output("I",'Certificado INAPROF.pdf', true);
?>