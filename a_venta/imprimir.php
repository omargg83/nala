<?php
	require_once("db_.php");
	$idventa=$_REQUEST['idventa'];
	$pd = $db->venta($idventa);
	$id=$pd->idventa;
	$idcliente=$pd->idcliente;
	$idsucursal=$pd->idsucursal;
	$lugar=$pd->lugar;
	$entregar=$pd->entregar;
	$dentrega=$pd->dentrega;
	$estado=$pd->estado;
	$fecha=$pd->fecha;
	$subtotal=$pd->subtotal;
	$iva=$pd->iva;
	$total=$pd->total;
	$tipo_pago=$pd->tipo_pago;

	$cliente=$db->cliente($idcliente);
	$nombre_cli=$cliente->nombre;
	$correo_cli=$cliente->correo;
	$telefono_cli=$cliente->telefono;

	$pedido = $db->ventas_pedido($idventa);

	$suc=  $db->sucursal_info();
	$tiend=  $db->tienda_info();

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
	$pdf->ezText("Cliente: ".$nombre_cli,10);
	$pdf->ezText("Fecha y hora: ".$fecha,10);
	//$pdf->ezText("Expedido en: Pachuca Hgo.",10);
	$pdf->ezText("Ticket #: ".$idventa,12);
	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	foreach($pedido as $ped){
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

	$pdf->ezText("Tipo de pago: ".$tipo_pago,10);
	$pdf->ezText(" ",10);

	if ($tiend->desglose=='1'){
	$pdf->ezText("Sub-Total: $".$subtotal,10,array('justification' => 'right'));
	$pdf->ezText("Iva: $".$iva,10,array('justification' => 'right'));
	}
	$pdf->ezText("Total:".moneda($total),12,array('justification' => 'right'));
	$pdf->ezText(" ",10);
	$pdf->ezText($tiend->mensaje,12,array('justification' => 'center'));
	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();
?>
