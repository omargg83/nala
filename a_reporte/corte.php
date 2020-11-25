<?php
	require_once("db_.php");

	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	echo $desde;
	echo $hasta;
	/*
	$suc=  $db->sucursal_info();
	$tiend=  $db->tienda_info();
	$pd=$db->corte_caja();

	set_include_path('../lib/pdf2/src/'.PATH_SEPARATOR.get_include_path());
	include 'Cezpdf.php';

	$pdf = new Cezpdf('C8','portrait','color',array(255,255,255)); //ticket 58mm en mozilla
	//$pdf = new Cezpdf('C7','portrait','color',array(255,255,255));
	$pdf->selectFont('Helvetica');
	// la imagen solo aparecera si antes del codigo ezStream se pone ob_end_clean como se muestra al final men
	$pdf->ezImage("../img/logoimp.jpg", 0, 100, 'none', 'center');
	$pdf->ezText($tiend->razon,10,array('justification' => 'center'));
	$pdf->ezText($suc->ubicacion,10,array('justification' => 'center'));
	$pdf->ezText("Codigo Postal: ".$suc->cp,10,array('justification' => 'center'));
	$pdf->ezText($suc->ciudad." ".$suc->estado,10,array('justification' => 'center'));
	$pdf->ezText($suc->tel1,10,array('justification' => 'center'));
	$pdf->ezText($suc->tel2,10,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	foreach($pd as $key){
		$data[$contar]=array(
			'No.'=>$contar+1,
			'Desc.'=>$key->fecha,
			'Cant.'=>number_format($key->total),
			'Costo'=>$key->tipo_pago
		);
		$contar++;
	}

	//$pdf->ezText("Expedido en: Pachuca Hgo.",10);

	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();

	*/
?>
