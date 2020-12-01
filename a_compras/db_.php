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

class Compras extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('COMPRAS', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='COMPRAS'";
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
	public function compras_lista($pagina){
		try{
			$pagina=$pagina*$_SESSION['pagina'];
			$sql="SELECT * FROM compras where idsucursal='".$_SESSION['idsucursal']."' order by numero desc limit $pagina,".$_SESSION['pagina']."";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function compras_buscar($texto){
		try{
			$sql="SELECT * FROM compras where compras.nombre like '%$texto%' and idsucursal='".$_SESSION['idsucursal']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function compra($id){
		try{
		  $sql="select * from compras where idcompra=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function borrar_compra(){
		$idcompra=$_REQUEST['idcompra'];
		$x=$this->borrar('compras',"idcompra",$idcompra);
		return $x;
	}

	public function proveedores_lista(){
		$sql="SELECT * FROM proveedores where idtienda='".$_SESSION['idtienda']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}
	public function entrada($id){
		$sql="SELECT
			bodega.idbodega,
			bodega.cantidad,
			bodega.c_precio,
			productos_catalogo.nombre,
			productos_catalogo.codigo
		FROM
			bodega
		LEFT OUTER JOIN productos ON productos.idproducto = bodega.idproducto
		LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
		WHERE
		bodega.idcompra='$id'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}
	public function guardar_entrada(){
		$arreglo =array();
		$idcompra=$_REQUEST['idcompra'];
		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>$_REQUEST['nombre']);
		}
		if (isset($_REQUEST['idproveedor'])){
			$arreglo+=array('idproveedor'=>$_REQUEST['idproveedor']);
		}
		if (isset($_REQUEST['idcompra'])){
			$arreglo+=array('idcompra'=>$_REQUEST['idcompra']);
		}
		if (isset($_REQUEST['estado'])){
			$arreglo+=array('estado'=>$_REQUEST['estado']);
		}
		if (isset($_REQUEST['unico'])){
			$arreglo+=array('unico'=>$_REQUEST['unico']);
		}
		if($idcompra==0){
			$sql = "SELECT MAX(numero) FROM compras where idsucursal='".$_SESSION['idsucursal']."'";
			$statement = $this->dbh->prepare($sql);
			$statement->execute();
			$numero=$statement->fetchColumn()+1;

			$arreglo+=array('numero'=>$numero);
			$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
			$arreglo+=array('estado'=>"Activa");
			$x=$this->insert('compras', $arreglo);
		}
		else{
			$x=$this->update('compras',array('idcompra'=>$idcompra), $arreglo);
		}
		return $x;
	}
	public function agregacompra(){

		$idcatalogo=$_REQUEST['idcatalogo'];
		$preciocompra=$_REQUEST['precio'];
		$idcompra=$_REQUEST['idcompra'];
		$observaciones=$_REQUEST['observaciones'];
		$cantidad=$_REQUEST['cantidad'];

		if(strlen($cantidad)==0 or $cantidad==0){
			$arr=array();
			$arr+=array('error'=>1);
			$arr+=array('terror'=>"Verificar cantidad");
			return json_encode($arr);
		}

		////////////////////se da de alta el producto en la sucursal
		$sql="select * from productos where idsucursal='".$_SESSION['idsucursal']."' and idcatalogo='$idcatalogo'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		if($sth->rowCount()>0){
			$producto=$sth->fetch(PDO::FETCH_OBJ);
			$idproducto=$producto->idproducto;

			$arreglo=array();
			$arreglo+=array('preciocompra'=>$preciocompra);
			$this->update('productos',array('idproducto'=>$idproducto), $arreglo);
		}
		else{
			///////////////////
			////////////busca en el catalogo el tipo de producto
			$sql="select * from productos_catalogo where idcatalogo='$idcatalogo'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$catalogo=$sth->fetch(PDO::FETCH_OBJ);
			$tipo=$catalogo->tipo;

			$arreglo =array();
			$arreglo+=array('idcatalogo'=>$idcatalogo);
			$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
			$arreglo+=array('activo_producto'=>1);
			if($tipo==0){
				$arreglo+=array('cantidad'=>1);
			}
			else{
				$arreglo+=array('cantidad'=>0);
			}

			////////////busca el primer producto que encuentre para duplicarlo en sucursal
			$sql="select * from productos where idcatalogo='$idcatalogo' order by idproducto asc limit 1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			if($sth->rowCount()>0){
				$producto=$sth->fetch(PDO::FETCH_OBJ);
				$precio=$producto->precio;
				$stockmin=$producto->stockmin;
				$cantidad_mayoreo=$producto->cantidad_mayoreo;
				$precio_mayoreo=$producto->precio_mayoreo;
				$precio_distri=$producto->precio_distri;
				$mayoreo_cantidad=$producto->mayoreo_cantidad;
				$distri_cantidad=$producto->distri_cantidad;
				$esquema=$producto->esquema;
				$monto_mayor=$producto->monto_mayor;
				$monto_distribuidor=$producto->monto_distribuidor;
			}
			else{
				$precio=0;
				$stockmin=0;
				$cantidad_mayoreo=10;
				$precio_mayoreo=0;
				$precio_distri=0;
				$mayoreo_cantidad=0;
				$distri_cantidad=0;
				$esquema=0;
				$monto_mayor=1000;
				$monto_distribuidor=3000;
				$stockmin=1;
			}

			$arreglo+=array('preciocompra'=>$preciocompra);
			$arreglo+=array('precio'=>$precio);
			$arreglo+=array('stockmin'=>$stockmin);
			$arreglo+=array('cantidad_mayoreo'=>$cantidad_mayoreo);
			$arreglo+=array('precio_mayoreo'=>$precio_mayoreo);
			$arreglo+=array('precio_distri'=>$precio_distri);
			$arreglo+=array('mayoreo_cantidad'=>$mayoreo_cantidad);
			$arreglo+=array('distri_cantidad'=>$distri_cantidad);
			$arreglo+=array('esquema'=>$esquema);
			$arreglo+=array('monto_mayor'=>$monto_mayor);
			$arreglo+=array('monto_distribuidor'=>$monto_distribuidor);
			$x=$this->insert('productos', $arreglo);
			///////////////////

			$ped=json_decode($x);
			if($ped->error==0){
				$idproducto=$ped->id;
			}
		}

		$arreglo=array();
		$arreglo+=array('fecha'=>date("Y-m-d H:i:s"));
		$arreglo+=array('fechaalta'=>date("Y-m-d H:i:s"));
		$arreglo+=array('idcompra'=>$idcompra);
		$arreglo+=array('idproducto'=>$idproducto);
		$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
		$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
		$arreglo+=array('observaciones'=>$observaciones);
		$arreglo+=array('cantidad'=>$cantidad);
		$arreglo+=array('c_precio'=>$preciocompra);
		$x=$this->insert('bodega', $arreglo);

		$arr=array();
		$arr+=array('id'=>$idcompra);
		$arr+=array('error'=>0);
		return json_encode($arr);
	}
	public function borrar_registro(){
		$idbodega=$_REQUEST['idbodega'];
		$x=$this->borrar('bodega',"idbodega",$idbodega);
		return $x;
	}
	public function finalizar_compra(){
		$idcompra=$_REQUEST['idcompra'];
		$arreglo =array();
		$arreglo+=array('estado'=>"Cerrada");
		return $this->update('compras',array('idcompra'=>$idcompra), $arreglo);
	}
}

$db = new Compras();
if(strlen($function)>0){
	echo $db->$function();
}
