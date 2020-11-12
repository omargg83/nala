<?php
	require_once("db_.php");
	$idventa=$_REQUEST['id'];
	$pd = $db->venta($idventa);
	$id=$pd['idventa'];
	$idcliente=$pd['idcliente'];
	$idtienda=$pd['idtienda'];
	$iddescuento=$pd['iddescuento'];
	$lugar=$pd['lugar'];
	$entregar=$pd['entregar'];
	$dentrega=$pd['dentrega'];
	$estado=$pd['estado'];
	$fecha=$pd['fecha'];
	$subtotal=$pd['subtotal'];
	$iva=$pd['iva'];
	$total=$pd['total'];
	$tipo_pago=$pd['tipo_pago'];

	$cliente=$db->cliente($idcliente);
	$nombre_cli=$cliente->nombre;
	$correo_cli=$cliente->correo;
	$telefono_cli=$cliente->telefono;

	$pedido = $db->ventas_pedido($idventa);

	set_include_path('../librerias15/pdf2/src/'.PATH_SEPARATOR.get_include_path());
	include 'Cezpdf.php';

	$pdf = new Cezpdf('C8','portrait','color',array(255,255,255)); //ticket 58mm en mozilla
	$pdf->selectFont('Helvetica');
	// la imagen solo aparecera si antes del codigo ezStream se pone ob_end_clean como se muestra al final men
	$pdf->ezImage("../img/logoimp.jpg", 0, 100, 'none', 'center');
//	$pdf->ezText("PUPILASER",10,array('justification' => 'center'));
	//$pdf->ezText("OPERADORA PLATHEA SA DE CV",10,array('justification' => 'center'));
	//$pdf->ezText("Rfc: OPL180514RA2",10,array('justification' => 'center'));
	$pdf->ezText("Blvd. Luis Donaldo Colosio 901 4to. piso, Col. Real del valle C.P.: 42086 Pachuca,Hgo.",10,array('justification' => 'center'));
	$pdf->ezText("Cel1. 771 719 7629",10,array('justification' => 'center'));
	$pdf->ezText("Cel2. 771 108 5081",10,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$pdf->ezText("Cliente: ".$nombre_cli,10);
	$pdf->ezText("Fecha y hora: ".$fecha,10);
//	$pdf->ezText("Expedido en: Pachuca Hgo.",10);
	$pdf->ezText("Ticket #: ".$idventa,12);
	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	foreach($pedido as $ped){
		$data[$contar]=array(
			'NO.'=>$contar+1,
			'Nombre'=>$ped->nombre,
			'Cantidad'=>number_format($ped->v_cantidad),
			'Precio'=>number_format($ped->v_precio,2)
		);
		$contar++;
	}
	$pdf->ezTable($data,"","",array('xPos'=>'left','xOrientation'=>'right','cols'=>array(
	'No.'=>array('width'=>15),
	'Desc.'=>array('width'=>65),
	'Cant.'=>array('width'=>20),
	'Costo'=>array('width'=>44)
),'fontSize' => 7));

	$pdf->ezText("Tipo de pago: ".$tipo_pago,10);
	$pdf->ezText(" ",10);
//	$pdf->ezText("Sub-Total: $".$subtotal,10,array('justification' => 'right'));
	//$pdf->ezText("Iva: $".$iva,10,array('justification' => 'right'));
	$pdf->ezText("Total:".moneda($total),12,array('justification' => 'right'));
	$pdf->ezText(" ",10);
	$pdf->ezText("¡Gracias por tu preferencia!",12,array('justification' => 'center'));
	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();
?>
