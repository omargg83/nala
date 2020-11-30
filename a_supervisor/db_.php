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
	public function producto_buscar($texto){
		$sql="SELECT
		productos_catalogo.nombre,
		productos_catalogo.codigo,
		productos_catalogo.tipo,
		productos.idproducto,
		productos.idcatalogo,
		productos.activo_producto,
		productos.cantidad,
		productos.precio,
		productos.preciocompra,
		productos.precio_mayoreo,
		productos.precio_distri,
		productos.stockmin,
		productos.idsucursal
		from productos
		LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
		where productos.idsucursal='".$_SESSION['idsucursal']."' and
		(nombre like '%$texto%'or
		descripcion like '%$texto%'or
		codigo like '%$texto%'
		)limit 50";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}

	public function productos_lista($pagina){
		try{
			$pagina=$pagina*$_SESSION['pagina'];
			$idsucursal=$_REQUEST['idsucursal'];
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
			$sql.=" limit $pagina,".$_SESSION['pagina']."";
			$sth = $this->dbh->prepare($sql);
			if(strlen($idsucursal)>0){
				$sth->bindValue(":idsucursal",$idsucursal);
			}
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function emitidasxsuc(){
		try{

			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];
			$idsucursal=$_REQUEST['idsucursal'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select venta.idventa, venta.idsucursal, venta.descuento, venta.factura, clientes.nombre as nombrecli, sucursal.nombre, venta.total, venta.fecha, venta.gtotal, venta.estado from venta
			left outer join clientes on clientes.idcliente=venta.idcliente
			left outer join sucursal on sucursal.idsucursal=venta.idsucursal where (venta.fecha BETWEEN :fecha1 AND :fecha2)";
			if(strlen($idsucursal)>0){
				$sql.=" and venta.idsucursal=:idsucursal";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			if(strlen($idsucursal)>0){
				$sth->bindValue(":idsucursal",$idsucursal);
			}
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function corte_cajaxsuc(){
		try{
			$idsucursal=$_REQUEST['idsucursal'];
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select sum(venta.total) as total, venta.fecha, venta.estado, venta.tipo_pago, sucursal.nombre from venta
			left outer join sucursal on sucursal.idsucursal=venta.idsucursal
			where (venta.fecha BETWEEN :fecha1 AND :fecha2) and venta.estado='Pagada' ";
			if(strlen($idsucursal)>0){
				$sql.=" and venta.idsucursal=:idsucursal";
			}
			$sql.=" GROUP BY tipo_pago";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			if(strlen($idsucursal)>0){
				$sth->bindValue(":idsucursal",$idsucursal);
			}
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
