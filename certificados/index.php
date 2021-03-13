<?php 


//require '../fpdf/fpdf.php';
require('../fpdf/mem_image.php');

$ruta = urlencode('https://inaprof.com/certificaciones/certificados/index.php?caso=50');
$imagePath = file_get_contents("http://localhost/certificaciones/certificados/qr.php?web=" . $ruta . ".png");

$pdf = new PDF_MemImage();
$pdf->AddFont('Dubai','','DUBAI-REGULAR.php');
$pdf->AddFont('Dubai','BI','DUBAI-MEDIUM.php');
$pdf->AddFont('Dubai','B','DUBAI-BOLD.php');
$pdf->AddPage('L');

$pdf->Image('imgs/fondo1.png',0,0,297, 210);

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
$pdf->MultiCell($anchoPag, 5, utf8_decode('ALCANTARA SOTO, JEAN MARTIN'), 0, 'C');
$pdf->Ln();
$pdf->SetFont('Dubai','', 11);
$pdf->SetX(75);
$pdf->Cell(54, 5, utf8_decode('Por su participación en calidad de '));
$pdf->SetFont('Dubai','B', 11);
$pdf->Cell(61, 5, utf8_decode('Asistente en el Curso Especialización'));
$pdf->SetFont('Dubai','', 11);
$pdf->Cell(100, 5, utf8_decode(', denominado:'));
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Dubai','B', 24);
$pdf->MultiCell($anchoPag, 5, utf8_decode('DERECHO CIVIL'), 0, 'C');
$pdf->Ln();
$pdf->SetFont('Dubai','BI', 14);
$pdf->MultiCell($anchoPag, 5, utf8_decode('PROPIEDAD, TERCERÍA DE PROPIEDAD Y PRESCRIPCIÓN ADQUISITIVA DE DOMINIO'), 0, 'C');
$pdf->Ln();
$pdf->SetFont('Dubai','', 12);
$pdf->SetX(30);
$pdf->MultiCell($anchoPag-60, 5, utf8_decode('Desarrolla del 08 de noviembre al 20 de noviembre del presente año, con una duración de 90 horas académicas, seminario a cargo del docente:'), 0, 'L');
$pdf->Ln();
$pdf->SetFont('Dubai','BI', 12);
$pdf->MultiCell($anchoPag, 5, utf8_decode('Dr. Julio César Escobar Andia'), 0, 'C');
$pdf->SetFont('Dubai','', 10);
$pdf->MultiCell($anchoPag/1.20, 5, utf8_decode('Dado y firmado en enero de 2020.'), 0, 'R');

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Dubai','B', 11);
$pdf->SetX($anchoPag/4);
$pdf->Cell($anchoPag/3, 5, utf8_decode('Luis Gonzales Villanueva'));
$pdf->Cell(61, 5, utf8_decode('Luis Gonzales Villanueva'));
$pdf->SetFont('Dubai','', 11);
$pdf->Ln();
$pdf->SetX($anchoPag/4+2);
$pdf->Cell($anchoPag/3, 5, utf8_decode('Coordinador Académico'));
$pdf->Cell(61, 5, utf8_decode('Coordinador Académico'));
$pdf->Ln();
$pdf->SetX($anchoPag/4-10);
$pdf->Cell($anchoPag/3, 5, utf8_decode('Dirección de Capacitación Profesional'));
$pdf->Cell(61, 5, utf8_decode('Dirección de Capacitación Profesional'));

$pdf->Ln();
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->SetFont('Dubai','B', 8);
$pdf->Cell(15, 4, utf8_decode('Aprobado'));
$pdf->Cell(61, 4, utf8_decode(': RESOLUCIÓN DIRECTORIAL N° 071-2020 DCP/INAPROF'));
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->Cell(15, 4, utf8_decode('Registro'));
$pdf->Cell(61, 4, utf8_decode(': Libro de Eventos Académicos'));
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->Cell(15, 4, utf8_decode('Tomo'));
$pdf->Cell(61, 4, utf8_decode(': XIX'));
$pdf->Ln();
$pdf->SetX($anchoPag/10);
$pdf->Cell(15, 4, utf8_decode('Código'));
$pdf->Cell(61, 4, utf8_decode(': 324 - 2020/DCP'));


$pdf->MemImage($imagePath,20,150,27);
$pdf->Image('../docentes/firmas/1615427858.jpg',80,135,20);



$pdf->Output("I",'Certificado INAPROF.pdf', true);
?>