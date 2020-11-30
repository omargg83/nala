<?php
	require_once("db_.php");

	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	$idusuario=$_REQUEST['idusuario'];
	$xdel=date("d-m-y",strtotime($desde));
	$xal=date("d-m-y",strtotime($hasta));


	$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
	$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";
	$sql="select sum(venta.total) as total, venta.fecha, venta.estado, venta.tipo_pago, usuarios.nombre as vendedor from venta
	LEFT OUTER JOIN usuarios ON usuarios.idusuario = venta.idusuario
	where venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.fecha BETWEEN :fecha1 AND :fecha2) and venta.estado='Pagada' ";
	if(strlen($idusuario)>0){
		$sql.=" and venta.idusuario=:idusuario";
	}
	$sql.=" GROUP BY tipo_pago";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":fecha1",$desde);
	$sth->bindValue(":fecha2",$hasta);
	if(strlen($idusuario)>0){
		$sth->bindValue(":idusuario",$idusuario);
	}
	$sth->execute();
	$res=$sth->fetchAll(PDO::FETCH_OBJ);
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
	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;
	$pdf->ezText("Corte de caja por usuario",12,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$pdf->ezText("Del: ".$xdel,10,array('justification' => 'left'));
	$pdf->ezText("Al: ".$xal,10,array('justification' => 'left'));
	$pdf->ezText(" ",10);
	foreach($res as $key){
		$pdf->ezText("Vendedor: ".$key->vendedor,10,array('justification' => 'left'));
		$pdf->ezText(" ",10);
		$data[$contar]=array(
			'Total'=>moneda($key->total),
			'Tipo'=>$key->tipo_pago
		);
		$contar++;
	}
	$pdf->ezTable($data,"","",array('xPos'=>'left','xOrientation'=>'right','cols'=>array(
	'Total'=>array('width'=>70),
	'Tipo'=>array('width'=>70)
	),'fontSize' => 7));

	//$pdf->ezText("Expedido en: Pachuca Hgo.",10);

	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();


?>
