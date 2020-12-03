<?php
	require_once("db_.php");
	$idtraspaso=$_REQUEST['idtraspaso'];
	$sucursal=$db->sucursal_lista();


	$traspaso = $db->traspaso($idtraspaso);
	$numero=$traspaso->numero;
	$nombre=$traspaso->nombre;
	$idsucursal=$traspaso->idsucursal;
	$estado=$traspaso->estado;
	$fecha=$traspaso->fecha;

	print_r($traspaso);
	$suc=  $db->sucursal_info();
	$tiend=  $db->tienda_info();
	set_include_path('../lib/pdf2/src/'.PATH_SEPARATOR.get_include_path());
	include 'Cezpdf.php';

	$pdf = new Cezpdf('C8','portrait','color',array(255,255,255)); //ticket 58mm en mozilla
	//$pdf = new Cezpdf('C7','portrait','color',array(255,255,255));
	$pdf->selectFont('Helvetica');
	// la imagen solo aparecera si antes del codigo ezStream se pone ob_end_clean como se muestra al final men

	if(strlen($tiend->logotipo)>0 and file_exists("../".$db->f_empresas."/".$tiend->logotipo)){
		$pdf->ezImage("../".$db->f_empresas."/".$tiend->logotipo, 0, 100, 'none', 'center');
	}
	else{
		$pdf->ezImage("../img/logoimp.jpg", 0, 100, 'none', 'center');
	}

	$pdf->ezText($tiend->razon,10,array('justification' => 'center'));
	$pdf->ezText($suc->ubicacion,10,array('justification' => 'center'));
	$pdf->ezText("Codigo Postal: ".$suc->cp,10,array('justification' => 'center'));
	$pdf->ezText($suc->ciudad." ".$suc->estado,10,array('justification' => 'center'));
	$pdf->ezText($suc->tel1,10,array('justification' => 'center'));
	$pdf->ezText($suc->tel2,10,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$pdf->ezText("Comprobante de traspaso de mercancia",10);
	$pdf->ezText("Fecha: ".$fecha,10);
	//$pdf->ezText("Expedido en: Pachuca Hgo.",10);

	$pdf->ezText(" ",10);

	$data=array();
	$contar=0;

	foreach($sucursal as $ped){
		$pdf->ezText("Traspaso a: ".$ped->nombre,10);
		$pdf->ezText("Dirección: ".$ped->ubicacion,10);
		$pdf->ezText(" ",10);
		$pdf->ezText("Estado del traspaso: ".$estado,10);

		$data[$contar]=array(
			'No.'=>$contar+1,
			'Desc.'=>$ped->nombre,
			'Cant.'=>number_format($ped->v_cantidad),
			'Costo'=>number_format($ped->v_precio*$ped->v_cantidad,2)
		);
		$contar++;
	}
	$pdf->ezTable($data,"","",array('xPos'=>'left','xOrientation'=>'right','cols'=>array(
	'No.'=>array('width'=>15),
	'Desc.'=>array('width'=>65),
	'Cant.'=>array('width'=>20),
	'Costo'=>array('width'=>44)
	),'fontSize' => 7));

	$pdf->ezText(" ",10);

	$pdf->ezText(" ",10);
	$pdf->ezText($tiend->mensaje,12,array('justification' => 'center'));
	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();
?>
