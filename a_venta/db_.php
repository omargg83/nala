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

class Venta extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;

	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('VENTA', $this->derecho)) {

		}
		else{
			include "../error.php";
			die();
		}
	}

	public function sucursal_info(){
		$sql="select * from sucursal where idsucursal='".$_SESSION['idsucursal']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_OBJ);
	}

	public function tienda_info(){
		$sql="select * from tienda where idtienda='".$_SESSION['idtienda']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_OBJ);
	}

	public function venta($id){
		$sql="select * from venta where idventa='$id'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_OBJ);
	}
	public function agregaventa(){
		$x="";
		$cliente="";
		$observaciones="";
		$cantidad="0";
		$precio="0";
		$tipo="0";
		$idventa=$_REQUEST['idventa'];
		$idcliente=$_REQUEST['idcliente'];
		$idproducto=$_REQUEST['idproducto'];

		$sql="select * from productos
		left outer join productos_catalogo on productos_catalogo.idcatalogo=productos.idcatalogo
		where idproducto='$idproducto'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$producto=$sth->fetch(PDO::FETCH_OBJ);

		if(!isset($_REQUEST['precio'])){
			$arreglo =array();
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"Falta precio");
			return json_encode($arreglo);
		}
		else{
			$precio=clean_var($_REQUEST['precio']);
		}

		if(!isset($_REQUEST['cantidad'])){
			$arreglo =array();
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"Falta cantidad");
			return json_encode($arreglo);
		}
		else{
			$cantidad=clean_var($_REQUEST['cantidad']);
		}

		if($precio==0){
			$arreglo =array();
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"Verificar precio $precio");
			return json_encode($arreglo);
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
				$arreglo+=array('terror'=>"Verificar cantidad");
				return json_encode($arreglo);
			}
		}

		try{
			if(isset($_REQUEST['idproducto'])){
				$precio=$_REQUEST['precio'];

				if($idventa==0){
					$date=date("Y-m-d H:i:s");
					$estado="Activa";
					$sql = "SELECT MAX(numero) FROM venta where idsucursal='".$_SESSION['idtienda']."'";
					$statement = $this->dbh->prepare($sql);
					$statement->execute();
					$numero=$statement->fetchColumn()+1;

					$arreglo=array();
					$arreglo+=array('idcliente'=>$idcliente);
					$arreglo+=array('estado'=>$estado);
					$arreglo+=array('numero'=>$numero);
					$arreglo+=array('fecha'=>$date);
					$arreglo+=array('idusuario'=>$_SESSION['idusuario']);
					$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
					$x=$this->insert('venta', $arreglo);
					$ped=json_decode($x);
					if($ped->error==0){
						$idventa=$ped->id;
					}
					else{
						return $x;
					}
				}
				else{
					$sql="select * from venta where idventa='$idventa'";
					$sth = $this->dbh->prepare($sql);
					$sth->execute();
					$venta=$sth->fetch(PDO::FETCH_OBJ);
					$numero=$venta->numero;
					$date=$venta->fecha;
					$estado=$venta->estado;
				}


				///////////////////////////recalcula esquemas
				$esquema=json_decode(self::esquemas($idproducto,$cantidad));


				$arreglo=array();
				$arreglo+=array('idventa'=>$idventa);
				$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
				$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
				$arreglo+=array('idproducto'=>$producto->idproducto);
				$arreglo+=array('nombre'=>$producto->nombre);
				$arreglo+=array('esquema'=>$producto->esquema);

				$arreglo+=array('v_cantidad'=>$cantidad);
				$total=$cantidad*$precio;
				$resta=$cantidad*-1;
				$arreglo+=array('cantidad'=>$resta);

				$arreglo+=array('v_precio_normal'=>$esquema->precio_normal);
				$arreglo+=array('v_total_normal'=>$esquema->total_menudeo);

				$arreglo+=array('v_precio_mayoreo'=>$esquema->precio_mayoreo);
				$arreglo+=array('v_total_mayoreo'=>$esquema->total_mayoreo);

				$arreglo+=array('v_precio_distribuidor'=>$esquema->precio_distribuidor);
				$arreglo+=array('v_total_distribuidor'=>$esquema->total_distribuidor);

				$arreglo+=array('mayoreo_cantidad'=>$producto->mayoreo_cantidad); //define el stock minimo para alcanzar el precio mayoreo y distribuidor en el esquema 2
				$arreglo+=array('distri_cantidad'=>$producto->distri_cantidad);

				$arreglo+=array('cantidad_mayoreo'=>$producto->cantidad_mayoreo);	//define el stock minimo para alcanzar el precio mayoreo y distribuidor en el esquema 1
				$arreglo+=array('monto_mayor'=>$producto->monto_mayor);
				$arreglo+=array('monto_distribuidor'=>$producto->monto_distribuidor);

				$arreglo+=array('v_precio'=>$precio);

				$x=$this->insert('bodega', $arreglo);
				$ped=json_decode($x);

				$total=$this->suma_venta($idventa);
				if($ped->error==0){
					$arreglo =array();
					$arreglo+=array('idventa'=>$idventa);
					$arreglo+=array('error'=>0);
					$arreglo+=array('numero'=>$numero);
					$arreglo+=array('estado'=>$estado);
					$arreglo+=array('total'=>$total);
					$fecha1 = date ( "Y-m-d" , strtotime($date) );
					$arreglo+=array('fecha'=>$fecha1);
					$arreglo+=array('error'=>0);
					return json_encode($arreglo);
				}
				else{
					return $x;
				}
			}
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function ventas_pedido($id){
		$sql="select * from bodega where idventa='$id' order by idbodega desc";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
	}
	public function borrar_venta(){
		$idbodega=$_REQUEST['idbodega'];
		$idventa=$_REQUEST['idventa'];
		$x=$this->borrar('bodega',"idbodega",$idbodega);
		$total=$this->suma_venta($idventa);

		$arreglo =array();
		$arreglo+=array('idventa'=>$idventa);
		$arreglo+=array('error'=>0);
		$arreglo+=array('total'=>$total);
		return json_encode($arreglo);
	}
	public function agregar_cliente(){
		try{
			$idventa=$_REQUEST['idventa'];
			$idcliente=$_REQUEST['idcliente'];
			if($idventa==0){
				$sql = "SELECT MAX(numero) FROM venta where idsucursal='".$_SESSION['idtienda']."'";
				$statement = $this->dbh->prepare($sql);
				$statement->execute();
				$numero=$statement->fetchColumn()+1;

				$arreglo=array();
				$arreglo+=array('idcliente'=>$idcliente);
				$arreglo+=array('estado'=>"Activa");
				$arreglo+=array('numero'=>$numero);

				$date=date("Y-m-d H:i:s");
				$arreglo+=array('fecha'=>$date);
				$arreglo+=array('idusuario'=>$_SESSION['idusuario']);
				$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
				$x=$this->insert('venta', $arreglo);
			}
			else{
				$arreglo=array();
				$arreglo+=array('idcliente'=>$idcliente);
				$x=$this->update('venta',array('idventa'=>$idventa), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function suma_venta($idventa){

		///////////////corregir este

			$sql="select sum(v_precio * v_cantidad) as total from bodega where idventa='$idventa' ";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$rex=$sth->fetch(PDO::FETCH_OBJ);

			$arreglo=array();
			$arreglo+=array('total'=>$rex->total);
			$this->update('venta',array('idventa'=>$idventa), $arreglo);
			return $rex->total;


		return 0;
	}


	public function finalizar_venta(){

		$total_g=clean_var($_REQUEST['total_g']);
		$efectivo_g=clean_var($_REQUEST['efectivo_g']);
		$cambio_g=clean_var($_REQUEST['cambio_g']);
		$tipo_pago=clean_var($_REQUEST['tipo_pago']);

		if($total_g>0){
			if($total_g<=$efectivo_g){
				if (isset($_REQUEST['idventa'])){$idventa=$_REQUEST['idventa'];}
				$arreglo =array();
				$arreglo+=array('tipo_pago'=>$tipo_pago);
				$arreglo+=array('estado'=>"Pagada");
				return $this->update('venta',array('idventa'=>$idventa), $arreglo);
			}
			else{
				return "favor de verificar";
			}
		}
		else{
			return "Debe de agregar un producto";
		}
	}
	public function cliente($idcliente){
		try{
			$sql="select * from clientes where idcliente='$idcliente'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function esquemas($idproducto,$cantidad){

		$sql="SELECT * from productos	where idproducto=:id";
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(":id",$idproducto);
		$sth->execute();
		$producto=$sth->fetch(PDO::FETCH_OBJ);


		///////////////comienzan variables
		$monto_mayor=$producto->monto_mayor;
		$monto_distribuidor=$producto->monto_distribuidor;
		$esquema=$producto->esquema;

		////////////////
		$precio=$producto->precio;
		$precio_mayoreo=$producto->precio_mayoreo;
		$precio_distri=$producto->precio_distri;
		$cantidad_mayoreo=$producto->cantidad_mayoreo;

		//////////////para esquema 2
		$mayoreo_cantidad=$producto->mayoreo_cantidad;
		$distri_cantidad=$producto->distri_cantidad;


		///////////////terminan variables

		if($esquema==0){//esquema 0
			$total_menudeo=($precio*$cantidad);
			$precio_f=$precio;

			$total_menudeo=$total_menudeo;
			$total_mayoreo=$total_menudeo;
			$total_distribuidor=$total_menudeo;

			$precio_mayoreo=$total_menudeo;
			$precio_distri=$total_menudeo;

		}

		else if($esquema==1){
			$total_menudeo=($precio*$cantidad);

			$precio_f=$precio;

			////////////////validaciones
			if($cantidad_mayoreo>0 and $monto_mayor>0 and $precio_mayoreo>0){
				///////////la buena
				$total_mayoreo=($precio_mayoreo*$cantidad);
			}
			else{
				$total_mayoreo=$total_menudeo;
			}
			if($cantidad_mayoreo>0 and $monto_distribuidor>0 and $precio_distri>0){
				///////////la buena
				$total_distribuidor=($precio_distri*$cantidad);
			}
			else{
				$total_distribuidor=$total_menudeo;
			}

			///////////////calculo
			$precio=$total_menudeo;

			if($total_menudeo>=$monto_mayor and $total_menudeo<$monto_distribuidor){
				////////////cuando es mayor que 1000
				$precio=$precio_mayoreo;
			}
			else if($total_menudeo>=$monto_distribuidor){
				/////////////cuando es mayor que 3000
				$precio=$precio_distri;
			}
			else if($cantidad>=$cantidad_mayoreo){
				//////////////cuando es mayor de 10
				$precio=$precio_mayoreo;
			}
			else{
				$precio=$precio_f;
			}
		}
		else if($esquema==2){//esquema 2
			$total_menudeo=($precio*$cantidad);
			$precio_f=$precio;
			//////////////validaciones
			if($precio_mayoreo>0 and $mayoreo_cantidad>0){
				$total_mayoreo=($precio_mayoreo*$cantidad);
			}
			else{
				$total_mayoreo=$total_menudeo;
			}

			if($precio_distri>0 and $distri_cantidad>0){
				$total_distribuidor=($precio_distri*$cantidad);
			}
			else{
				$total_distribuidor=$total_menudeo;
			}

			///////////////calculo
			$precio=$total_menudeo;
			if($cantidad>=$mayoreo_cantidad and $cantidad<$distri_cantidad){
				$precio=$precio_mayoreo;
			}
			else if($cantidad>=$distri_cantidad){
				$precio=$precio_distri;
			}
		}

		$arreglo =array();
		$arreglo+=array('error'=>0);
		$arreglo+=array('total_menudeo'=>$total_menudeo);
		$arreglo+=array('total_mayoreo'=>$total_mayoreo);
		$arreglo+=array('total_distribuidor'=>$total_distribuidor);

		$arreglo+=array('precio_normal'=>$precio_f);
		$arreglo+=array('precio_mayoreo'=>$precio_mayoreo);
		$arreglo+=array('precio_distribuidor'=>$precio_distri);

		$arreglo+=array('precio'=>$precio);
		return json_encode($arreglo);
	}
	public function esquemas_prev(){
		////////////////////checar aca que variables quedan
		$idproducto=clean_var($_REQUEST['idproducto']);
		$cantidad=clean_var($_REQUEST['cantidad']);

		return self::esquemas($idproducto,$cantidad);
	}
}

$db = new Venta();
if(strlen($function)>0){
	echo $db->$function();
}
