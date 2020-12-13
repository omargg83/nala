<?php
	require_once("db_.php");

	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	$idsucursal=$_REQUEST['idsucursal'];
	
	$suc=  $db->sucursal_info();
	$tiend=  $db->tienda_info();


	$xdel=date("d-m-y",strtotime($desde));
	$xal=date("d-m-y",strtotime($hasta));
	$fechayhora=new DateTime();

	$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
	$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";
	$sql="select venta.idventa, venta.numero, venta.idsucursal, venta.descuento, venta.factura, clientes.nombre as nombrecli, sucursal.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado from venta
	left outer join clientes on clientes.idcliente=venta.idcliente
	left outer join sucursal on sucursal.idsucursal=venta.idsucursal where (venta.fecha BETWEEN :fecha1 AND :fecha2)";
	if(strlen($idsucursal)>0){
		$sql.=" and venta.idsucursal=:idsucursal";
	}
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":fecha1",$desde);
	$sth->bindValue(":fecha2",$hasta);
	if(strlen($idsucursal)>0){
		$sth->bindValue(":idsucursal",$idsucursal);
	}
	$sth->execute();
	$res=$sth->fetchAll(PDO::FETCH_OBJ);



	set_include_path('../lib/pdf2/src/'.PATH_SEPARATOR.get_include_path());
	include 'Cezpdf.php';
	$pdf = new Cezpdf('letter','portrait','color',array(255,255,255));
//	$pdf = new Cezpdf('C8','portrait','color',array(255,255,255)); //ticket 58mm en mozilla
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
	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;
	$pdf->ezText("<b> Reporte de Ventas de la sucursal: ".$suc->nombre."</b>",12,array('justification' => 'center'));
//	$pdf->ezText(" ",10);
	$pdf->ezText("\nDel: ".$xdel,10,array('justification' => 'center'));
	$pdf->ezText("Al: ".$xal,10,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$totalven=0;
	foreach($res as $key){
		$data[$contar]=array(
			'Ticket #'=>$key->numero,
			'Fecha'=>$key->fecha,
			'Cliente'=>$key->nombrecli,
			'Total'=>moneda($key->total),
			'Estado'=>$key->estado

		);
			$totalven+=$key->total;
		$contar++;
	}
	$pdf->ezTable($data,"","",array('shadeHeadingCol' => array(127, 255, 0.7),'xPos'=>'center','xOrientation'=>'center','cols'=>array(
	'Ticket'=>array('width'=>70),
	'Fecha'=>array('width'=>120),
	'Cliente'=>array('width'=>110),
	'Total'=>array('width'=>90),
	'Estado'=>array('width'=>90)
),'fontSize' => 10));


$pdf->ezText(" ",5);
	$pdf->ezText("                          Fecha y Hora del reporte: ".$fechayhora->format('d-m-Y H:i:s'),7,array('justification' => 'left'));
	$pdf->ezText(" ",10);
	$pdf->ezText("            Total de ventas en el periodo: ".moneda($totalven),14);

	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();


?>
