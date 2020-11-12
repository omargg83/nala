<?php
	require_once("db_.php");
	$idcomision=$_REQUEST['id'];
	$tipo=$_REQUEST['tipo'];

	if($tipo==1){			/////////////////comision pdf
		$inven=$db->producto_editar($idcomision);
		$text=$inven->codigo;
		$nombre=$inven->nombre;

		include 'barcode.php';
		$filepath = (isset($_GET["filepath"])?$_GET["filepath"]:"");
		//$text = (isset($_GET["text"])?$_GET["text"]:"0");
		$size = (isset($_GET["size"])?$_GET["size"]:"20");
		$orientation = (isset($_GET["orientation"])?$_GET["orientation"]:"horizontal");
		$code_type = (isset($_GET["codetype"])?$_GET["codetype"]:"code128");
		$print = (isset($_GET["print"])&&$_GET["print"]=='true'?true:false);
		$sizefactor = (isset($_GET["sizefactor"])?$_GET["sizefactor"]:"1");

		$filepath="../tmp/archivo_".rand(1,1983).".png";
		$size=40;
		$archivo=barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );

		set_include_path('../lib/pdf2/src/'.PATH_SEPARATOR.get_include_path());
		include 'Cezpdf.php';
		$pdf = new Cezpdf('letter','portrait','color',array(255,255,255));
		$pdf->selectFont('Helvetica');

		$x=10;
		$y=730;

		//$pdf->rectangle($x,$y-30,210,70);
		$pdf->addPngFromFile($filepath,$x+30,$y,150);
		$pdf->addText($x,$y-10,10,$text,210,'center',0,0);
		$pdf->addText($x,$y-20,10,$nombre,210,'center',0,0);
		$pdf->ezStream();
	}


?>
