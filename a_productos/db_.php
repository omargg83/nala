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

class Productos extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('PRODUCTOS', $this->derecho)) {

		}
		else{
			include "../error.php";
			die();
		}
	}
	public function producto_buscar($texto){
		$sql="select * from productos_catalogo where productos_catalogo.nombre like '%$texto%' and idtienda='".$_SESSION['idtienda']."' limit 50";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
  }
	public function productos_lista(){
		try{
			$sql="SELECT * from productos_catalogo where activo_catalogo=1 order by tipo asc, idcatalogo asc limit 50";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function borrar_producto(){
		if (isset($_REQUEST['idcatalogo'])){ $idcatalogo=$_REQUEST['idcatalogo']; }
		return $this->borrar('productos_catalogo',"idcatalogo",$idcatalogo);
	}

	public function producto_edit($id){
		try{
			$sql="select * from productos_catalogo where idcatalogo=:id and idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$id);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function guardar_producto(){
		try{
			if (isset($_REQUEST['idcatalogo'])){
				$idcatalogo=$_REQUEST['idcatalogo'];
			}
			$arreglo =array();
			$tipo="";
			$imei="";
			$codigo="";

			if (isset($_REQUEST['codigo'])){
				$codigo=$_REQUEST['codigo'];
				$arreglo += array('codigo'=>$_REQUEST['codigo']);
			}
			if (isset($_REQUEST['nombre'])){
				$arreglo += array('nombre'=>$_REQUEST['nombre']);
			}

			if (isset($_REQUEST['descripcion'])){
				$arreglo += array('descripcion'=>$_REQUEST['descripcion']);
			}
			if (isset($_REQUEST['activo_catalogo'])){
				$arreglo += array('activo_catalogo'=>$_REQUEST['activo_catalogo']);
			}
			if (isset($_REQUEST['categoria'])){
				$arreglo += array('categoria'=>$_REQUEST['categoria']);
			}

			if (isset($_REQUEST['tipo'])){
				$tipo=$_REQUEST['tipo'];
				$arreglo += array('tipo'=>$_REQUEST['tipo']);
			}
			$x="";

			if($idcatalogo==0){
				$arreglo+=array('fechaalta'=>date("Y-m-d H:i:s"));
				$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
				$x=$this->insert('productos_catalogo', $arreglo);
				$ped=json_decode($x);

				if($ped->error==0){
					$idcatalogo=$ped->id;

					$arreglo =array();
					if($tipo==0){
						$arreglo+=array('cantidad'=>1);
					}
					$monto_mayor=1000;
					$monto_distribuidor=3000;
					$stockmin=1;
					$cantidad_mayoreo=10;

					$arreglo+=array('preciocompra'=>0);
					$arreglo+=array('precio'=>0);
					$arreglo+=array('monto_mayor'=>$monto_mayor);

					$arreglo+=array('monto_distribuidor'=>$monto_distribuidor);
					$arreglo+=array('stockmin'=>$stockmin);
					$arreglo+=array('cantidad_mayoreo'=>$cantidad_mayoreo);
					$arreglo+=array('mayoreo_cantidad'=>0);
					$arreglo+=array('distri_cantidad'=>0);
					$arreglo+=array('precio_mayoreo'=>0);
					$arreglo+=array('precio_distri'=>0);

					$arreglo+=array('idcatalogo'=>$idcatalogo);
					$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
					$this->insert('productos', $arreglo);

					$this->cantidad_update($idcatalogo,$tipo);

					if(strlen(trim($codigo))==0){
						$codigo="9".str_pad($idcatalogo, 8, "0", STR_PAD_LEFT);
						$arreglo =array();
						$arreglo = array('codigo'=>$codigo);
						$this->update('productos_catalogo',array('idcatalogo'=>$idcatalogo), $arreglo);
					}
				}
				else{
					return $x;
				}
			}
			else{
				$arreglo+=array('fechamod'=>date("Y-m-d H:i:s"));
				$x=$this->update('productos_catalogo',array('idcatalogo'=>$idcatalogo), $arreglo);

				$this->cantidad_update($idcatalogo,$tipo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function genera_barras(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$codigo="9".str_pad($id, 8, "0", STR_PAD_LEFT);
			$arreglo =array();

			$arreglo = array('codigo'=>$codigo);
			$arreglo+=array('fechamod'=>date("Y-m-d H:i:s"));
			$x=$this->update('productos_catalogo',array('id'=>$id), $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function sucursal(){
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
	public function categoria(){
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

}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
