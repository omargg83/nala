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

	$pedido = $db->traspaso_pedido($idtraspaso);
	//print_r($pedido);

	$sql="SELECT * from sucursal where idsucursal=:id";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":id",$traspaso->idsucursal);
	$sth->execute();
	$infotraspaso=$sth->fetch(PDO::FETCH_OBJ);


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
	$pdf->ezText("Comprobante de traspaso de mercancia",10,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$pdf->ezText("Fecha Traspaso: ".$fecha,10);
	$pdf->ezText("Numero de traspaso: ".$numero,10);
	$pdf->ezText("Identificador: ".$nombre,10);
	$pdf->ezText(" ",10);
	$pdf->ezText("De: ".$suc->nombre,10);
//	$pdf->ezText($suc->ubicacion,10,array('justification' => 'center'));
//	$pdf->ezText($suc->ciudad." ".$suc->estado,10,array('justification' => 'center'));
	$pdf->ezText("A:   ".$infotraspaso->nombre,10);
//	$pdf->ezText("Dirección: ".$infotraspaso->ubicacion,10);
//	$pdf->ezText("Ciudad y Edo.: ".$infotraspaso->ciudad." ,".$infotraspaso->estado,10);
	$pdf->ezText(" ",10);
	$pdf->ezText("Detalle envio: ",7);

	$data=array();
	$contar=0;

		foreach($pedido as $ped){
		$data[$contar]=array(
			'No.'=>$contar+1,
			'Nombre'=>$ped->nombre,
			'Cant.'=>number_format($ped->v_cantidad)
		);
		$contar++;
		}
	$pdf->ezTable($data,"","",array('xPos'=>'left','xOrientation'=>'right','cols'=>array(
	'No.'=>array('width'=>15),
	'Nombre.'=>array('width'=>65),
	'Cant.'=>array('width'=>20)
	),'fontSize' => 7));

	$pdf->ezText(" ",10);
	$pdf->ezText("Estado de mercancia: ".$estado,10);

	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();


?>
