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

class Cliente extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('PROVEEDORES', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='PROVEEDORES'";
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
	public function provedores_lista(){
		try{

			$sql="SELECT * FROM proveedores where idtienda='".$_SESSION['idtienda']."'";
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
			$sql="SELECT * FROM proveedores where proveedores.nombre like '%$texto%' and idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function provedor($id){
		try{
		  $sql="select * from proveedores where idproveedor=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_provedor(){
		$x="";
		$arreglo =array();
		$idproveedor=$_REQUEST['idproveedor'];
		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>$_REQUEST['nombre']);
		}
		if (isset($_REQUEST['emailp'])){
			$arreglo+=array('emailp'=>$_REQUEST['emailp']);
		}
		if (isset($_REQUEST['telp'])){
			$arreglo+=array('telp'=>$_REQUEST['telp']);
		}
		if (isset($_REQUEST['dirp'])){
			$arreglo+=array('dirp'=>$_REQUEST['dirp']);
		}

		if($idproveedor==0){
			$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
			$x=$this->insert('proveedores', $arreglo);
		}
		else{
			$x=$this->update('proveedores',array('idproveedor'=>$idproveedor), $arreglo);
		}
		return $x;
	}
	public function borrar_proveedor(){
		if (isset($_REQUEST['idproveedor'])){ $idproveedor=$_REQUEST['idproveedor']; }
		return $this->borrar('proveedores',"idproveedor",$idproveedor);
	}
}

$db = new Cliente();
if(strlen($function)>0){
	echo $db->$function();
}
