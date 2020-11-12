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
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('CLIENTES', $this->derecho)) {

		}
		else{
			include "../error.php";
			die();
		}
	}
	public function clientes_lista(){
		try{

			$sql="SELECT * FROM clientes where idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function clientes_buscar($texto){
		try{
			$sql="SELECT * FROM clientes where clientes.nombre like '%$texto%' and idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function cliente($id){
		try{
		  $sql="select * from clientes where idcliente=:id";
		  $sth = $this->dbh->prepare($sql);
		  $sth->bindValue(":id",$id);
		  $sth->execute();
		  return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
		  return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_cliente(){
		$x="";
		$arreglo =array();
		$idcliente=$_REQUEST['idcliente'];

		if (isset($_REQUEST['razon_social'])){
			$arreglo+=array('razon_social'=>clean_var($_REQUEST['razon_social']));
		}
		if (isset($_REQUEST['rfc'])){
			$arreglo+=array('rfc'=>clean_var($_REQUEST['rfc']));
		}
		if (isset($_REQUEST['cfdi'])){
			$arreglo+=array('cfdi'=>clean_var($_REQUEST['cfdi']));
		}
		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>clean_var($_REQUEST['nombre']));
		}
		if (isset($_REQUEST['direccion'])){
			$arreglo+=array('direccion'=>clean_var($_REQUEST['direccion']));
		}
		if (isset($_REQUEST['entrecalles'])){
			$arreglo+=array('entrecalles'=>clean_var($_REQUEST['entrecalles']));
		}
		if (isset($_REQUEST['numero'])){
			$arreglo+=array('numero'=>clean_var($_REQUEST['numero']));
		}
		if (isset($_REQUEST['colonia'])){
			$arreglo+=array('colonia'=>clean_var($_REQUEST['colonia']));
		}
		if (isset($_REQUEST['ciudad'])){
			$arreglo+=array('ciudad'=>clean_var($_REQUEST['ciudad']));
		}
		if (isset($_REQUEST['cp'])){
			$arreglo+=array('cp'=>clean_var($_REQUEST['cp']));
		}
		if (isset($_REQUEST['pais'])){
			$arreglo+=array('pais'=>clean_var($_REQUEST['pais']));
		}
		if (isset($_REQUEST['estado'])){
			$arreglo+=array('estado'=>clean_var($_REQUEST['estado']));
		}
		if (isset($_REQUEST['observaciones'])){
			$arreglo+=array('observaciones'=>clean_var($_REQUEST['observaciones']));
		}
		if (isset($_REQUEST['telefono'])){
			$arreglo+=array('telefono'=>clean_var($_REQUEST['telefono']));
		}
		if (isset($_REQUEST['correo'])){
			$arreglo+=array('correo'=>clean_var($_REQUEST['correo']));
		}


		if($idcliente==0){
			$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
			$x=$this->insert('clientes', $arreglo);
		}
		else{
			$x=$this->update('clientes',array('idcliente'=>$idcliente), $arreglo);
		}
		return $x;
	}
	public function borrar_cliente(){
		if (isset($_REQUEST['id'])){ $id=$_REQUEST['id']; }
		return $this->borrar('clientes',"idcliente",$id);
	}
}

$db = new Cliente();
if(strlen($function)>0){
	echo $db->$function();
}
