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

class Caja extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('CAJAS', $this->derecho)) {

		}
		else{
			include "../error.php";
			die();
		}
	}
	public function cajas_lista(){
		try{

			$sql="SELECT * FROM cajas where idsucursal='".$_SESSION['idsucursal']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function provedores_buscar($texto){
		try{
			$sql="SELECT * FROM cajas where cajas.nombrecaja like '%$texto%' and idsucursal='".$_SESSION['idsucursal']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function caja($id){
		try{
		  $sql="select * from cajas where idcaja=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_caja(){
		$x="";
		$arreglo =array();
		$idcaja=$_REQUEST['idcaja'];
		if (isset($_REQUEST['nombrecaja'])){
			$arreglo+=array('nombrecaja'=>$_REQUEST['nombrecaja']);
		}

		if($idcaja==0){
			$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
			$x=$this->insert('cajas', $arreglo);
		}
		else{
			$x=$this->update('cajas',array('idcaja'=>$idcaja), $arreglo);
		}
		return $x;
	}
	public function borrar_caja(){
		if (isset($_REQUEST['idcaja'])){ $idcaja=$_REQUEST['idcaja']; }
		return $this->borrar('cajas',"idcaja",$idcaja);
	}
}

$db = new Caja();
if(strlen($function)>0){
	echo $db->$function();
}
