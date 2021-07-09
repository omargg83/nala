<?php
	require_once("db_.php");

	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	$idusuario=$_REQUEST['idusuario'];
	$xdel=date("d-m-y",strtotime($desde));
	$xal=date("d-m-y",strtotime($hasta));
	$fechayhora=new DateTime();

	$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
	$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";
	$sql="SELECT venta.*, clientes.nombre as nombrecli, sucursal.nombre,bodega.v_cantidad, bodega.v_precio,	bodega.v_total,	bodega.nombre, bodega.observaciones, bodega.cliente, productos.preciocompra, usuarios.nombre as vendedor FROM	bodega
		LEFT OUTER JOIN venta ON venta.idventa = bodega.idventa
		LEFT OUTER JOIN usuarios ON usuarios.idusuario = venta.idusuario
		left outer join productos on productos.idproducto=bodega.idproducto
		LEFT OUTER JOIN clientes ON clientes.idcliente = venta.idcliente
		LEFT OUTER JOIN sucursal ON sucursal.idsucursal = venta.idsucursal
		where bodega.idventa and venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.fecha BETWEEN :fecha1 AND :fecha2)";
		if(strlen($idusuario)>0){
			$sql.=" and venta.idusuario=:idusuario";
		}
		$sql.=" order by idventa desc";
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
	$pdf->ezText("<b> Reporte de ganancias por producto: ".$suc->nombre."</b>",10,array('justification' => 'center'));
//	$pdf->ezText(" ",10);
	$pdf->ezText("\nDel: ".$xdel,10,array('justification' => 'center'));
	$pdf->ezText("Al: ".$xal,10,array('justification' => 'center'));
	$pdf->ezText(" ",10);
	$totalven=0;
	foreach($res as $key){
		$data[$contar]=array(
			'Ticket #'=>$key->numero,
			'Fecha'=>$key->fecha,
			'Producto'=>$key->nombre,
			'Cant.'=>$key->v_cantidad,
			'Precio U.'=>moneda($key->v_precio),
			'Total'=>moneda($key->v_cantidad*$key->v_precio),
			'Precio Compra.'=>moneda($key->preciocompra),
			'Ganancia'=>moneda(($key->v_cantidad*$key->v_precio)- ($key->v_cantidad*$key->preciocompra))

		);
			$totalven+=(($key->v_cantidad*$key->v_precio)- ($key->v_cantidad*$key->preciocompra));
		$contar++;
	}
	$pdf->ezTable($data,"","",array('shadeHeadingCol' => array(127, 255, 0.7),'xPos'=>'center','xOrientation'=>'center','cols'=>array(
	'Ticket'=>array('width'=>50),
	'Fecha'=>array('width'=>100),
	'Producto'=>array('width'=>110),
	'Cant.'=>array('width'=>30),
	'Precio U.'=>array('width'=>50),
	'Total'=>array('width'=>50),
	'Estado'=>array('width'=>50),
	'Vendedor'=>array('width'=>50)
),'fontSize' => 8));


$pdf->ezText(" ",5);
	$pdf->ezText("    Fecha y Hora del reporte: ".$fechayhora->format('d-m-Y H:i:s'),7,array('justification' => 'left'));
	$pdf->ezText(" ",10);
	$pdf->ezText(" Total de ganancias en el periodo: ".moneda($totalven),14);

	$pdf->ezText(" ",10);
	$data=array();
	$contar=0;

	if (ob_get_contents()) ob_end_clean();
	$pdf->ezStream();


?>
