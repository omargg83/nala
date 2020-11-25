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
	public function compras_lista(){
		try{
			$sql="SELECT * FROM compras where idsucursal='".$_SESSION['idsucursal']."' order by numero desc";
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
		$precio=$_REQUEST['precio'];
		$idcompra=$_REQUEST['idcompra'];
		$observaciones=$_REQUEST['observaciones'];
		$cantidad=$_REQUEST['cantidad'];

		////////////////////se da de alta el producto en la sucursal
		$sql="select * from productos where idsucursal='".$_SESSION['idsucursal']."' and idcatalogo='$idcatalogo'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		if($sth->rowCount()>0){
			$producto=$sth->fetch(PDO::FETCH_OBJ);
			$idproducto=$producto->idproducto;

			$arreglo=array();
			$arreglo+=array('preciocompra'=>$precio);
			$this->update('productos',array('idproducto'=>$idproducto), $arreglo);
		}
		else{
			$arreglo=array();
			$arreglo+=array('idcatalogo'=>$idcatalogo);
			$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
			$arreglo+=array('preciocompra'=>$precio);
			$arreglo+=array('activo_producto'=>1);
			$x=$this->insert('productos', $arreglo);

			$ped=json_decode($x);
			if($ped->error==0){
				$idproducto=$ped->id;
			}
		}

		$arreglo=array();
		$arreglo+=array('idcompra'=>$idcompra);
		$arreglo+=array('idproducto'=>$idproducto);
		$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
		$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
		$arreglo+=array('observaciones'=>$observaciones);
		$arreglo+=array('cantidad'=>$cantidad);
		$arreglo+=array('c_precio'=>$precio);
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
