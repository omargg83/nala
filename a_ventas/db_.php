<?php
require_once("../control_db.php");

if($_SESSION['des']==1 and strlen($function)==0)
{
	echo "<div class='alert alert-primary' role='alert' style='font-size:10px'>";
	$arrayx=explode('/', $_SERVER['SCRIPT_NAME']);
	echo print_r($arrayx);
	echo "<br>";
	echo print_r($_REQUEST);
	echo "</div>";
}


class Venta extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;

	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('VENTAREGISTRO', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='VENTAREGISTRO'";
			$stmt= $this->dbh->query($sql);

			$row =$stmt->fetchObject();
			$this->nivel_personal=$row->nivel;
			$this->nivel_captura=$row->captura;
		}
		else{
			include "../error.php";
			die();
		}
	}

	public function ventas_lista(){
		$sql="select venta.idventa, venta.numero, venta.idsucursal, clientes.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado from venta
		left outer join clientes on clientes.idcliente=venta.idcliente
		where venta.idsucursal='".$_SESSION['idsucursal']."' and venta.estado='Activa' order by venta.numero desc";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}
	public function ventas_pagadas(){
		$sql="select venta.idventa, venta.numero, venta.idsucursal, clientes.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado from venta
		left outer join clientes on clientes.idcliente=venta.idcliente
		where venta.idsucursal='".$_SESSION['idsucursal']."' and venta.estado='Pagada' order by venta.numero desc";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}
	public function ventas_buscar($texto){
		$sql="select venta.idventa, venta.numero, venta.idsucursal, clientes.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado from venta
		left outer join clientes on clientes.idcliente=venta.idcliente
		where venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.numero like '%$texto%' or clientes.nombre like '%$texto%') order by venta.numero desc limit 100";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
  }

	public function selecciona_cita(){
		try{

			$idcita=$_REQUEST['idcita'];
			$idventa=$_REQUEST['idventa'];

			$sql="SELECT * from citas where idcitas=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$idcita);
			$sth->execute();
			$row=$sth->fetch(PDO::FETCH_OBJ);

			$fech = new DateTime($row->fecha);
			$fecha=$fech->format('d-m-Y');
			$hora=$fech->format('H');
			$minuto=$fech->format('i');

			$estatus=$row->estatus;
			$observaciones=$row->observaciones;
			$idcliente=$row->idcliente;
			$cubiculo=$row->cubiculo;
			$atiende=$row->atiende;
			$servicio=$row->servicio;
			$precio=$row->precio;

			$cliente=$this->cliente($idcliente);
			$nombre_cli=$cliente->profesion." ".$cliente->nombre." ".$cliente->apellidop." ".$cliente->apellidom;
			$correo_cli=$cliente->correo;
			$telefono_cli=$cliente->telefono;


			echo "<form id='form_producto' action='' data-lugar='a_ventas/db_' data-destino='a_ventas/editar' data-funcion='agregaventa'>";
			echo "<input type='hidden' name='idventa' id='idventa' value='$idventa' readonly>";
			echo "<input type='hidden' name='idcita' id='idcita' value='$idcita' readonly>";
			echo "<input type='hidden' name='tipo' id='tipo' value='99' readonly>";
			echo "<div class='row'>";

				echo "<div class='col-3'>";
					echo "<label>Fecha</label>";
					echo "<input type='text' class='form-control form-control-sm fechaclass' id='fechac' name='fechac' value='$fecha' readonly>";
				echo "</div>";

				echo "<div class='col-3'>";
					echo "<label>Hora</label>";
					echo "<input type='text' class='form-control form-control-sm fechaclass' id='horac' name='horac' value='$hora:$minuto' readonly>";
				echo "</div>";

				echo "<div class='col-3'>";
					echo "<label>Estado</label>";
					echo "<input type='text' class='form-control form-control-sm fechaclass' id='estadoc' name='estadoc' value='$estatus' readonly>";
				echo "</div>";

				echo "<div class='col-6'>";
					echo "<label>Nombre:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='nombrec' id='nombrec' value='".$nombre_cli."' readonly>";
				echo "</div>";

				echo "<div class='col-6'>";
					echo "<label>Correo:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='correoc' id='correoc' value='".$correo_cli."' readonly>";
				echo "</div>";

				echo "<div class='col-2'>";
					echo "<label>Cubiculo</label>";
					echo "<input type='text' class='form-control form-control-sm' name='cubiculoc' id='cubiculoc' value='".$cubiculo."' readonly>";
				echo "</div>";

				echo "<div class='col-4'>";
					echo "<label>Atiende</label>";
					echo "<input type='text' class='form-control form-control-sm' name='atiendec' id='atiendec' value='".$atiende."' readonly>";
				echo "</div>";

				echo "<div class='col-6'>";
					echo "<label>Servicio</label>";
					echo "<input type='text' class='form-control form-control-sm' name='servicioc' id='servicioc' value='".$servicio."' readonly>";
				echo "</div>";

				echo "<div class='col-12'>";
					echo "<label>Notas:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='observacionesc' id='observacionesc' value='".$observaciones."' readonly>";
				echo "</div>";

				echo "<div class='col-3'>";
					echo "<label>Precio</label>";
					echo "<input type='text' class='form-control form-control-sm' name='precio' id='precio' value='$precio'>";
				echo "</div>";

			echo "</div>";
			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					echo "<div class='btn-group'>";
						echo "<button type='submit' class='btn btn-outline-info btn-sm'><i class='fas fa-cart-plus'></i>Agregar</button>";
						echo "<button type='button' class='btn btn-outline-primary btn-sm' data-dismiss='modal'><i class='fas fa-sign-out-alt'></i>Cerrar</button>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "</form>";

		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function buscar($texto){

		$texto=trim($texto);
		if(strlen($texto)>0){
			$sql="select venta.idventa, venta.idtienda, venta.iddescuento, venta.factura, clientes.nombre, et_tienda.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado, et_descuento.nombre as descuento from venta
			left outer join clientes on clientes.idcliente=venta.idcliente
			left outer join et_descuento on et_descuento.iddescuento=venta.iddescuento
			left outer join et_tienda on et_tienda.id=venta.idtienda where venta.idtienda='".$_SESSION['idtienda']."' and (venta.idventa like '%$texto%' or clientes.nombre like '%$texto%' or venta.estado like '%$texto%' or venta.total like '%$texto%') order by venta.fecha desc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
	}
	public function clientes_lista(){

		$sql="SELECT * FROM clientes";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll();
	}
	public function tiendas_lista(){

		$sql="SELECT * FROM et_tienda where id='".$_SESSION['idtienda']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll();
	}
	public function descuento_lista(){

		$sql="SELECT * FROM et_descuento";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll();
	}
	public function productos_vendidos(){
		try{


			$idusuario=$_REQUEST['idusuario'];
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="SELECT venta.idventa, venta.idtienda,	venta.iddescuento,	venta.factura, clientes.nombre, et_tienda.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado,			bodega.v_cantidad, bodega.v_precio,	bodega.v_total,	bodega.nombre, bodega.observaciones, bodega.cliente, usuarios.nombre as vendedor FROM	bodega
				LEFT OUTER JOIN venta ON venta.idventa = bodega.idventa
				LEFT OUTER JOIN usuarios ON usuarios.idusuario = venta.idusuario
				left outer join productos on productos.id=bodega.idproducto
				LEFT OUTER JOIN clientes ON clientes.idcliente = venta.idcliente
				LEFT OUTER JOIN et_tienda ON et_tienda.id = venta.idtienda
				where bodega.idventa and (venta.fecha BETWEEN :fecha1 AND :fecha2)";
				if(strlen($idusuario)>0){
					$sql.=" and venta.idusuario=:idusuario";
				}
				$sql.=" order by idventa desc";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			if(strlen($idusuario)>0){
				$sth->bindValue(":idusuario",$idusuario);
			}
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
}

$db = new Venta();
if(strlen($function)>0){
	echo $db->$function();
}
