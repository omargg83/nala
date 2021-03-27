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

class Categoria extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if($_SESSION['nivel']==66){
			$this->nivel_personal=0;
			$this->nivel_captura=1;
		}
		else if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('INVENTARIO', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='INVENTARIO'";
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
	public function categoria_lista(){
		try{

			$sql="SELECT * FROM categorias where idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function categorias_buscar($texto){
		try{
			$sql="SELECT * FROM categorias where categorias.nombre like '%$texto%' and idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function categoria($id){
		try{
		  $sql="select * from categorias where idcat=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_categoria(){
		$x="";
		$arreglo =array();
		$idcat=$_REQUEST['idcat'];
		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>$_REQUEST['nombre']);
		}

		if($idcat==0){
			$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
			$x=$this->insert('categorias', $arreglo);
		}
		else{
			$x=$this->update('categorias',array('idcat'=>$idcat), $arreglo);
		}
		return $x;
	}
	public function borrar_categoria(){
		if (isset($_REQUEST['id'])){ $id=$_REQUEST['id']; }
		return $this->borrar('categorias',"id",$id);
	}
}

$db = new Categoria();
if(strlen($function)>0){
	echo $db->$function();
}
