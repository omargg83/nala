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
class Traspaso extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;

	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('TRASPASOS', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='TRASPASOS'";
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
	public function traspaso($idtraspaso){
		try{
			$sql="SELECT * FROM traspasos where idtraspaso=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$idtraspaso);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			echo $e;
			return "Database access FAILED!";
		}
	}
	public function traspasos_buscar($texto){
		$sql="select * from traspasos	where traspasos.idtienda='".$_SESSION['idtienda']."' and (traspasos.numero like '%$texto%' or traspasos.nombre like '%$texto%') limit 100";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
  }

	public function traspasos_lista(){
		try{
			$sql="SELECT * FROM traspasos where idtienda='".$_SESSION['idtienda']."' and iddesde='".$_SESSION['idsucursal']."' order by idtraspaso desc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			echo $e;
			return "Database access FAILED!";
		}
	}
	public function sucursal_lista(){
		$sql="SELECT * FROM sucursal where idtienda='".$_SESSION['idtienda']."' and idsucursal!='".$_SESSION['idsucursal']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}
	public function guardar_traspaso(){
		$arreglo =array();
		if (isset($_REQUEST['idtraspaso'])){$idtraspaso=$_REQUEST['idtraspaso'];}

		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>clean_var($_REQUEST['nombre']));
		}
		if (isset($_REQUEST['estado'])){
			$arreglo+=array('estado'=>clean_var($_REQUEST['estado']));
		}
		if (isset($_REQUEST['fecha'])){
			$arreglo+=array('fecha'=>$_REQUEST['fecha']);
		}
		if (isset($_REQUEST['idsucursal'])){
			$arreglo+=array('idsucursal'=>clean_var($_REQUEST['idsucursal']));
		}
		if($idtraspaso>0){
			$x=$this->update('traspasos',array('idtraspaso'=>$idtraspaso),$arreglo);
		}
		else{
			$sql = "SELECT MAX(numero) FROM traspasos where idtienda='".$_SESSION['idtienda']."'";
			$statement = $this->dbh->prepare($sql);
			$statement->execute();
			$numero=$statement->fetchColumn()+1;

			$arreglo+=array('numero'=>$numero);
			$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
			$arreglo+=array('iddesde'=>$_SESSION['idsucursal']);
			$arreglo+=array('idusuario'=>$_SESSION['idusuario']);
			$x=$this->insert('traspasos', $arreglo);
		}
		return $x;
	}
	public function traspaso_pedido($id){
		$sql="select * from bodega where idtraspaso='$id' order by idbodega desc";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}

	public function agregatraspaso(){
		$idtraspaso=$_REQUEST['idtraspaso'];
		$idproducto=$_REQUEST['idproducto'];
		$cantidad=$_REQUEST['cantidad'];


		$sql="select * from productos
		left outer join productos_catalogo on productos_catalogo.idcatalogo=productos.idcatalogo
		where idproducto='$idproducto'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$producto=$sth->fetch(PDO::FETCH_OBJ);

		if(!isset($_REQUEST['cantidad'])){
			$arreglo =array();
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"Falta cantidad");
			return json_encode($arreglo);
		}
		else{
			$cantidad=clean_var($_REQUEST['cantidad']);
		}

		if($cantidad==0){
			$arreglo =array();
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"Verificar cantidad");
			return json_encode($arreglo);
		}

		if($producto->tipo==3){
			$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$idproducto'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$cantidad_bg=$sth->fetch(PDO::FETCH_OBJ);
			if ($cantidad>$cantidad_bg->total){
				$arreglo =array();
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Verificar existencias");
				return json_encode($arreglo);
			}
		}

		$arreglo=array();
		$arreglo+=array('idtraspaso'=>$idtraspaso);
		$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
		$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
		$arreglo+=array('idproducto'=>$producto->idproducto);
		$arreglo+=array('v_cantidad'=>$cantidad);

		$cantidad=$cantidad*-1;
		$arreglo+=array('cantidad'=>$cantidad);
		$arreglo+=array('nombre'=>$producto->nombre);
		$x=$this->insert('bodega', $arreglo);
		//$ped=json_decode($x);
		return $x;
	}
	public function borrar_traspaso(){
		$idbodega=$_REQUEST['idbodega'];
		$idtraspaso=$_REQUEST['idtraspaso'];
		$x=$this->borrar('bodega',"idbodega",$idbodega);

		$arreglo =array();
		$arreglo+=array('idtraspaso'=>$idtraspaso);
		$arreglo+=array('error'=>0);
		return json_encode($arreglo);
	}

	public function recepcion_lista(){
		try{
			$sql="SELECT * FROM traspasos where idtienda='".$_SESSION['idtienda']."' and idsucursal='".$_SESSION['idsucursal']."' and estado='Enviada' order by idtraspaso desc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			echo $e;
			return "Database access FAILED!";
		}
	}
	public function enviar_traspaso(){
		$idtraspaso=$_REQUEST['idtraspaso'];
		$arreglo =array();
		$arreglo+=array('estado'=>"Enviada");
		return $this->update('traspasos',array('idtraspaso'=>$idtraspaso), $arreglo);
	}
}
$db = new Traspaso();
if(strlen($function)>0){
	echo $db->$function();
}
