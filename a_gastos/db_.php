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

class Gastos extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('GASTOS', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='GASTOS'";
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

	public function gastos_lista(){
		try{
			$sql="SELECT * FROM gastos where idtienda='".$_SESSION['idtienda']."' and idsucursal= '".$_SESSION['idsucursal']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function gastos_buscar($texto){
		try{
			$sql="SELECT * FROM gastos where gastos.gasto like '%$texto%' and idtienda='".$_SESSION['idtienda']."' and idsucursal= '".$_SESSION['idsucursal']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function gastos_edit($idgastos){
		try{
			$sql="SELECT * FROM gastos where idgastos=:idgastos ";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":idgastos",$idgastos);
			$sth->execute();
			$res=$sth->fetch();
			return $res;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	function guardar_gastos(){
		$x="";
		if (isset($_REQUEST['idgastos'])){$id=$_REQUEST['idgastos'];}
		$arreglo =array();
		if (isset($_REQUEST['fecha'])){
			$fx=explode("-",$_REQUEST['fecha']);
			$arreglo+=array('fecha'=>$fx['2']."-".$fx['1']."-".$fx['0']);
		}
		if (isset($_REQUEST['gasto'])){
			$arreglo+=array('gasto'=>$_REQUEST['gasto']);
		}
		if (isset($_REQUEST['descripcion'])){
			$arreglo+=array('descripcion'=>$_REQUEST['descripcion']);
		}
		if (isset($_REQUEST['costo'])){
			$arreglo+=array('costo'=>$_REQUEST['costo']);
		}

		if($id==0){
			$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
			$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
			$x.=$this->insert('gastos', $arreglo);
		}
		else{
			$x.=$this->update('gastos',array('idgastos'=>$id), $arreglo);
		}
		return $x;
	}

	public function borrar_gasto(){
		if (isset($_REQUEST['id'])){ $idgastos=$_REQUEST['id']; }
		return $this->borrar('gastos',"idgastos",$idgastos);
	}

}


$db = new Gastos();
if(strlen($function)>0){
	echo $db->$function();
}
