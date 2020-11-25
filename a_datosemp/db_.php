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

class Datos_tienda extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('DATOSEMP', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='DATOSEMP'";
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
	public function tienda_lista(){
		try{
			$sql="SELECT * FROM tienda where idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function tienda($id){
		try{

		  $sql="select * from tienda where idtienda=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_tienda(){
		$x="";
		$arreglo =array();

		$idtienda=$_REQUEST['idtienda'];
		if (isset($_REQUEST['razon'])){
			$arreglo+=array('razon'=>$_REQUEST['razon']);
		}
		if (isset($_REQUEST['calle'])){
			$arreglo+=array('calle'=>$_REQUEST['calle']);
		}
		if (isset($_REQUEST['no'])){
			$arreglo+=array('no'=>$_REQUEST['no']);
		}
		if (isset($_REQUEST['col'])){
			$arreglo+=array('col'=>$_REQUEST['col']);
		}
		if (isset($_REQUEST['ciudad'])){
			$arreglo+=array('ciudad'=>$_REQUEST['ciudad']);
		}
		if (isset($_REQUEST['estado'])){
			$arreglo+=array('estado'=>$_REQUEST['estado']);
		}
		if (isset($_REQUEST['desglose'])){
			$arreglo+=array('desglose'=>$_REQUEST['desglose']);
		}
		if (isset($_REQUEST['mensaje'])){
			$arreglo+=array('mensaje'=>$_REQUEST['mensaje']);
		}

		if($idtienda==0){
			$x=$this->insert('tienda', $arreglo);
		}
		else{
			$x=$this->update('tienda',array('idtienda'=>$_SESSION['idtienda']), $arreglo);
		}
		return $x;
	}
}

$db = new Datos_tienda();
if(strlen($function)>0){
	echo $db->$function();
}
