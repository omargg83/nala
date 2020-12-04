<?php
	require_once("db_.php");
	$idcatalogo=$_REQUEST['idcatalogo'];


	$sql="SELECT * from productos_catalogo where idcatalogo=:id ";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":id",$idcatalogo);
	$sth->execute();
	$inven=$sth->fetch(PDO::FETCH_OBJ);

	$text=$inven->codigo;
	$nombre=$inven->nombre;

	include 'barcode.php';
	$filepath = (isset($_GET["filepath"])?$_GET["filepath"]:"");
	//$text = (isset($_GET["text"])?$_GET["text"]:"0");
	$size = (isset($_GET["size"])?$_GET["size"]:"10");
	$orientation = (isset($_GET["orientation"])?$_GET["orientation"]:"horizontal");
	$code_type = (isset($_GET["codetype"])?$_GET["codetype"]:"code128");
	$print = (isset($_GET["print"])&&$_GET["print"]=='true'?true:false);
	$sizefactor = (isset($_GET["sizefactor"])?$_GET["sizefactor"]:"1");

	$filepath="../tmp/archivo_".rand(1,1983).".png";
	$size=40;
	$archivo=barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );

	set_include_path('../lib/pdf2/src/'.PATH_SEPARATOR.get_include_path());
	include 'Cezpdf.php';
	//$pdf = new Cezpdf('letter','portrait','color',array(255,255,255));
		$pdf = new Cezpdf('C7','portrait','color',array(255,255,255)); //ticket 88mm en mozilla
	$pdf->selectFont('Helvetica');

	$x=-10;
	$y=330;
	$pdf->ezText("Impresión de Codigo de Barras",10,array('justification' => 'center'));
	$pdf->rectangle($x+20,$y-40,220,80);
	$pdf->addPngFromFile($filepath,$x+50,$y,150);
	$pdf->addText($x+20,$y-10,10,$text,210,'center',0,0);
	$pdf->addText($x+20,$y-20,10,$nombre,210,'center',0,0);
	$pdf->ezStream();



?>
