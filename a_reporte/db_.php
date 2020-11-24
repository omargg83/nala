<?php
require_once("../control_db.php");

if($_SESSION['des']==1 and strlen($function)==0)
{
	echo "<div class='alert alert-primary' role='alert'>";
	$arrayx=explode('/', $_SERVER['SCRIPT_NAME']);
	echo print_r($arrayx);
	echo "<hr>";
	echo print_r($_REQUEST);
	echo "</div>";
}


class Venta extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;

	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('REPORTES', $this->derecho)) {

		}
		else{
			include "../error.php";
			die();
		}
	}


	public function sucursal_info(){
		$sql="select * from sucursal where idsucursal='".$_SESSION['idsucursal']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_OBJ);
	}

	public function tienda_info(){
		$sql="select * from tienda where idtienda='".$_SESSION['idtienda']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_OBJ);
	}


	public function emitidas(){
		try{

			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select venta.idventa, venta.idsucursal, venta.descuento, venta.factura, clientes.nombre as nombrecli, sucursal.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado from venta
			left outer join clientes on clientes.idcliente=venta.idcliente
			left outer join sucursal on sucursal.idsucursal=venta.idsucursal where venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.fecha BETWEEN :fecha1 AND :fecha2)";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function productos_vendidos(){
		try{
			$idusuario=$_REQUEST['idusuario'];
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="SELECT venta.idventa, venta.idsucursal,	venta.descuento,	venta.factura, clientes.nombre as nombrecli, sucursal.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado,bodega.v_cantidad, bodega.v_precio,	bodega.v_total,	bodega.nombre, bodega.observaciones, bodega.cliente, usuarios.nombre as vendedor FROM	bodega
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

	public function corte_caja(){
		try{
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select sum(venta.total) as total, venta.fecha, venta.estado, venta.tipo_pago from venta
			where venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.fecha BETWEEN :fecha1 AND :fecha2) and venta.estado='Pagada' GROUP BY tipo_pago ";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function corte_caja_usuario(){
		try{
			$idusuario=$_REQUEST['idusuario'];
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select sum(venta.total) as total, venta.fecha, venta.estado, venta.tipo_pago, usuarios.nombre as vendedor from venta
			LEFT OUTER JOIN usuarios ON usuarios.idusuario = venta.idusuario
			where venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.fecha BETWEEN :fecha1 AND :fecha2) and venta.estado='Pagada' ";
			if(strlen($idusuario)>0){
				$sql.=" and venta.idusuario=:idusuario";
			}
			$sql.=" GROUP BY tipo_pago";
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
