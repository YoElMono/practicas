<?php
	require 'barcode.php';
	require 'main/templates/complementos/fpdf/fpdf.php'; 
	$num = $arr[0];
	$inf = $arr[1][0];
	$des = $arr[2];
	$des = strip_tags($des);
	$nom = 'barcode';
	$ext = 'gif';
	if($ext == 'jpg') $ext1 = 'JPEG';
	else $ext1 = strtoupper($ext);
	new barcode($num,$nom,$ext,'',"",'code128');
	$num = str_split($num);
	$nn='';
	for($i=0;$i<count($num);$i++){
		$nn .= $num[$i].' ';
	}
	$num = $nn;
		$pdf = new FPDF('P','mm','etiqueta');
		$pdf->SetMargins(0,1);
		$pdf->SetAutoPageBreak(true,0);
		$pdf->SetFont('Arial','B',13);
		$pdf->AddPage();
		$pdf->Cell(0,4,utf8_decode($inf),0,1,'C');
		$pdf->Cell(0,4,utf8_decode($des),0,1,'C');
		$pdf->Image($nom.'.'.$ext,4,9,80,10,$ext1);
		$pdf->Cell(0,15,'',0,1);
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(0,2,utf8_decode($num),0,1,'C');	
		$pdf->Output('prueba', 'i');
	//unlink($nom.'.'.$ext);
?>