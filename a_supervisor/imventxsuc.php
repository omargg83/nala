<?php
	require_once("db_.php");

	set_include_path('../lib/pdf2/src/'.PATH_SEPARATOR.get_include_path());
	include 'Cezpdf.php';
	$pdf = new Cezpdf('letter','portrait','color',array(255,255,255));
//	$pdf = new Cezpdf('C8','portrait','color',array(255,255,255)); //ticket 58mm en mozilla
	//$pdf = new Cezpdf('C7','portrait','color',array(255,255,255));
	$pdf->selectFont('Helvetica');
	// la imagen solo aparecera si antes del codigo ezStream se pone ob_end_clean como se muestra al final men

	$idsucursal=$_REQUEST['idsucursal'];
	$fechayhora=new DateTime();

	$sql="SELECT
	productos_catalogo.nombre,
	productos_catalogo.codigo,
	productos_catalogo.tipo,
	productos.*
	from productos
	LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
	where productos_catalogo.tipo<>0 ";
	if(strlen($idsucursal)>0){
		$sql.=" and productos.idsucursal=:idsucursal";
	}
	$sth = $db->dbh->prepare($sql);
	if(strlen($idsucursal)>0){
		$sth->bindValue(":idsucursal",$idsucursal);
	}
	$sth->execute();
	$res=$sth->fetchAll(PDO::FETCH_OBJ);

	$suc=  $db->sucursal_info();
	$tiend=  $db->tienda_info();



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
					$pdf->ezText("<b> Inventario de la sucursal: ".$suc->nombre."</b>",10,array('justification' => 'center'));
				//	$pdf->ezText(" ",10);

					$pdf->ezText(" ",10);

		if (empty($res)) {
							$pdf->ezText("<b>No hay información disponible en el periodo seleccionado </b>",12,array('justification' => 'center'));
							$pdf->ezText(" ",10);
					}
					else {
							foreach($res as $key){

								$sql="select sum(cantidad) as total from bodega where idsucursal='$key->idsucursal' and idproducto='$key->idproducto'";
								$sth = $db->dbh->prepare($sql);
								$sth->execute();
								$cantidad=$sth->fetch(PDO::FETCH_OBJ);
								if(strlen($cantidad->total)>0){
									$exist=$cantidad->total;
								}
								else{
									$exist=0;
								}

								$data[$contar]=array(
									'Código'=>$key->codigo,
									'Nombre'=>$key->nombre,
									'Existencia'=>$exist,
									'Precio de venta'=>moneda($key->precio)

								);
								$contar++;
							}
								$pdf->ezTable($data,"","",array('shadeHeadingCol' => array(127, 255, 0.7),'xPos'=>'center','xOrientation'=>'center','cols'=>array(
								'Código'=>array('width'=>100),
								'Nombre'=>array('width'=>180),
								'Existencia'=>array('width'=>80),
								'Precio de venta'=>array('width'=>90)
							),'fontSize' => 8));
						}

$pdf->ezText(" ",5);
	$pdf->ezText("    Fecha y Hora del reporte: ".$fechayhora->format('d-m-Y H:i:s'),7,array('justification' => 'left'));
	$pdf->ezText(" ",10);

	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();


?>
