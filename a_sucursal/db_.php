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

class Sucursal extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('SUCURSAL', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='SUCURSAL'";
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
	public function sucursal_lista(){
		try{
			$sql="SELECT * FROM sucursal where idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function sucursal($id){
		try{
		  $sql="select * from sucursal where idsucursal=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_sucursal(){
		$x="";
		$arreglo =array();

		$idsucursal=$_REQUEST['idsucursal'];
		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>$_REQUEST['nombre']);
		}
		if (isset($_REQUEST['ciudad'])){
			$arreglo+=array('ciudad'=>$_REQUEST['ciudad']);
		}
		if (isset($_REQUEST['tel1'])){
			$arreglo+=array('tel1'=>$_REQUEST['tel1']);
		}
		if (isset($_REQUEST['tel2'])){
			$arreglo+=array('tel2'=>$_REQUEST['tel2']);
		}
		if (isset($_REQUEST['cp'])){
			$arreglo+=array('cp'=>$_REQUEST['cp']);
		}
		if (isset($_REQUEST['ubicacion'])){
			$arreglo+=array('ubicacion'=>$_REQUEST['ubicacion']);
		}
		if (isset($_REQUEST['estado'])){
			$arreglo+=array('estado'=>$_REQUEST['estado']);
		}
		if (isset($_REQUEST['tipoticket'])){
			$arreglo+=array('tipoticket'=>$_REQUEST['tipoticket']);
		}

		if($idsucursal==0){
			$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
			$x=$this->insert('sucursal', $arreglo);
		}
		else{
			$x=$this->update('sucursal',array('idsucursal'=>$idsucursal), $arreglo);
		}
		return $x;
	}
	public function borrar_sucursal(){
		$idsucursal=$_REQUEST['idsucursal'];
		$x=$this->borrar('sucursal',"idsucursal",$idsucursal);
		return $x;
	}
}

$db = new Sucursal();
if(strlen($function)>0){
	echo $db->$function();
}
