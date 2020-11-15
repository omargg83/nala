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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Pedidos extends Sagyc{

	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('CITAS', $this->derecho)) {

		}
		else{
			include "../error.php";
			die();
		}
	}
	public function busca_cliente($texto){
		try{


			$sql="SELECT * from clientes where nombre like '%$texto%' limit 100";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<table class='table table-sm'>";
			echo "<tr><th>-</th><th>Prof.</th><th>Nombre </th><th>Correo</th><th>Teléfono</th></tr>";
			foreach($sth->fetchAll(PDO::FETCH_OBJ) as $key){
				echo "<tr>";
					echo "<td>";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='cliente_add(".$key->idcliente.")' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</td>";
					echo "<td>";
							echo $key->profesion;
					echo "</td>";
					echo "<td>";
							echo $key->nombre." ".$key->apellidop." ".$key->apellidom;
					echo "</td>";
					echo "<td>";
							echo $key->correo;
					echo "</td>";
					echo "<td>";
							echo $key->telefono;
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function agrega_cliente(){
		try{
			$idcita=$_REQUEST['idcita'];
			$idcliente=$_REQUEST['idcliente'];

			if($idcita==0){
				$arreglo=array();
				$arreglo+=array('idcliente'=>$idcliente);
				$date=date("Y-m-d H:i:s");
				$arreglo+=array('estatus'=>"PENDIENTE");
				$arreglo+=array('fecha'=>$date);
				$x=$this->insert('citas', $arreglo);
			}
			else{
				$arreglo=array();
				$arreglo+=array('idcliente'=>$idcliente);
				$x=$this->update('citas',array('idcitas'=>$idcita), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function citas_lista(){
		try{
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from pedidos
				left outer join clientes on clientes.id=citas.idcliente
				where citas.idcitas like '%$texto%' or citas.estatus like '%$texto%' or clientes.nombre like '%$texto' order by citas.idcitas desc limit 100";
			}
			else{
				$sql="SELECT * from citas order by citas.idcitas desc";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function guardar_cita(){
		try{
			if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
			$arreglo =array();
			$hora=$_REQUEST['hora'];
			$minuto=$_REQUEST['minuto'];

			$hora_fin=$_REQUEST['hora_fin'];
			$minuto_fin=$_REQUEST['minuto_fin'];
			if (!isset($_REQUEST['idcliente']) or strlen($_REQUEST['idcliente'])==0 or $_REQUEST['idcliente']==0){
				$resp=array();
				$resp+=array('id'=>0);
				$resp+=array('error'=>1);
				$resp+=array('terror'=>'Falta seleccionar cliente');
				return json_encode($resp);
			}

			if (isset($_REQUEST['fecha']) and strlen($_REQUEST['fecha'])>0){
				$fx=explode("-",$_REQUEST['fecha']);
				$arreglo+=array('fecha'=>$fx['2']."-".$fx['1']."-".$fx['0']." $hora:$minuto:00");
			}

			if (isset($_REQUEST['fecha']) and strlen($_REQUEST['fecha'])>0){
				$fx=explode("-",$_REQUEST['fecha']);
				$arreglo+=array('fecha_fin'=>$fx['2']."-".$fx['1']."-".$fx['0']." $hora_fin:$minuto_fin:00");
			}
			if (isset($_REQUEST['estatus'])){
				$arreglo+= array('estatus'=>$_REQUEST['estatus']);
			}
			if (isset($_REQUEST['idcliente'])){
				$arreglo+= array('idcliente'=>$_REQUEST['idcliente']);
			}
			if (isset($_REQUEST['observaciones'])){
				$arreglo+= array('observaciones'=>$_REQUEST['observaciones']);
			}
			if (isset($_REQUEST['cubiculo'])){
				$arreglo+= array('cubiculo'=>$_REQUEST['cubiculo']);
			}
			if (isset($_REQUEST['atiende'])){
				$arreglo+= array('atiende'=>$_REQUEST['atiende']);
			}
			if (isset($_REQUEST['servicio'])){
				$arreglo+= array('servicio'=>$_REQUEST['servicio']);
			}
			if (isset($_REQUEST['precio'])){
				$arreglo+= array('precio'=>$_REQUEST['precio']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('citas', $arreglo);
			}
			else{
				$x=$this->update('citas',array('idcitas'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function servicios_lista(){
		try{
			$sql="SELECT
			productos_catalogo.nombre,
			productos_catalogo.codigo,
			productos_catalogo.tipo,
			productos.idproducto,
			productos.activo_producto,
			productos.cantidad,
			productos.precio,
			productos.preciocompra,
			productos.preciom,
			productos.preciod,
			productos.stockmin,
			productos.idsucursal
			from productos
			LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
			where productos.idsucursal='".$_SESSION['idsucursal']."' and productos.activo_producto=1 and productos_catalogo.tipo=0 limit 50";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function atiende(){
		try{
			$sql="select * from usuarios";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function editar_cita($id){
		try{
			$sql="SELECT * from citas where idcitas=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
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

	public function borrar_cita(){
		if (isset($_REQUEST['idcita'])){$id=$_REQUEST['idcita'];}
		return $this->borrar('citas',"idcitas",$id);
	}


	public function info($id){
		try{
			$sql="SELECT * from citas where idcitas=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function cambiar_dia(){
		try{
			$horario=$_REQUEST['horario'];
			$idcita=$_REQUEST['idcita'];

			$fx=explode(" ",$horario);
			$fecha=$fx[0];
			$hora=$fx[1];

			$fx=explode("/",$fecha);
			$dia=$fx[0];
			$mes=$fx[1];
			$anio=$fx[2];
			$arreglo=array();
			$arreglo+= array('fecha'=>"$anio-$mes-$dia $hora");
			$x=$this->update('citas',array('idcitas'=>$idcita), $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}

	}
	public function cambiar_hora(){
		try{
			$idcita=$_REQUEST['idcita'];
			$horario=$_REQUEST['horario'];

			$horario2=$_REQUEST['horario2'];

			$cita=$this->info($idcita);


			$fx=explode(" ",$horario);
			$fecha=$fx[0];
			$hora=$fx[1];

			$fx=explode("/",$fecha);
			$dia=$fx[0];
			$mes=$fx[1];
			$anio=$fx[2];
			$fecha1=$anio."-".$mes."-".$dia." ".$hora;

			$fx=explode(" ",$horario2);
			$fecha=$fx[0];
			$hora=$fx[1];

			$fx=explode("/",$fecha);
			$dia=$fx[0];
			$mes=$fx[1];
			$anio=$fx[2];
			$fecha2=$anio."-".$mes."-".$dia." ".$hora;

			$arreglo=array();
			$arreglo+= array('fecha'=>$fecha1);
			$arreglo+= array('fecha_fin'=>$fecha2);
			$x=$this->update('citas',array('idcitas'=>$idcita), $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}

	}
	public function citas_calendario($inicio,$fin){
		try{
			$inicio=$inicio." 00:00:00";
			$fin=$fin." 23:59:59";
			$sql="SELECT * from citas left outer join clientes on clientes.idcliente=citas.idcliente
			where citas.fecha between '$inicio' and '$fin' order by citas.idcitas desc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function busca_producto(){
		try{
			$texto=$_REQUEST['texto'];
			$idcitas=$_REQUEST['idcitas'];

			$sql="SELECT
			productos_catalogo.nombre,
			productos_catalogo.codigo,
			productos_catalogo.tipo,
			productos.idproducto,
			productos.idcatalogo,
			productos.activo_producto,
			productos.cantidad,
			productos.precio,
			productos.preciocompra,
			productos.preciom,
			productos.preciod,
			productos.stockmin,
			productos.idsucursal
			from productos
			LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
			where productos.idsucursal='".$_SESSION['idsucursal']."' and
		  (nombre like '%$texto%'or
			descripcion like '%$texto%'or
		  codigo like '%$texto%'
			)limit 50";

			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":texto","%$texto%");
			$sth->bindValue(":tienda",$_SESSION['idtienda']);
			$sth->execute();
			$res=$sth->fetchAll();

			echo "<div class='row'>";
			echo "<table class='table table-sm' style='font-size:14px'>";
			echo  "<tr>";
			echo  "<th>-</th>";
			echo  "<th>Código</th>";
			echo  "<th>Nombre</th>";
			echo  "<th>Existencias</th>";
			echo  "<th>Precio</th>";
			echo "</tr>";
			if(count($res)>0){
				foreach ($res as $key) {
					echo  "<tr id=".$key['id']." class='edit-t'>";
					echo  "<td>";
					echo  "<div class='btn-group'>";
					if($key['tipo']==0 or $key['tipo']==2 or ($key['tipo']==3 and $key['cantidad']>0) or ($key['tipo']==4 and strlen($key['idventa'])==0)){
							echo  "<button type='button' onclick='sel_prod(".$key['id'].",$idcitas)' class='btn btn-outline-secondary btn-sm' title='Seleccionar articulo'><i class='far fa-hand-pointer'></i></button>";
					}
					echo  "</div>";
					echo  "</td>";

					echo  "<td>";
						echo  "<span style='font-size:12px'>";
						echo  "<B>BARRAS: </B>".$key["codigo"]."  ";
						echo  "</span>";
					echo  "</td>";

					echo  "<td>";
					echo  $key["nombre"];
					echo  "</td>";

					echo  "<td class='text-center'>";
					echo  $key["cantidad"];
					echo  "</td>";

					echo  "<td align='right'>";
						echo 	moneda($key["precio"]);
					echo  "</td>";

					echo  "</tr>";
				}
			}
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function selecciona_producto(){
		try{
			$idproducto=$_REQUEST['idproducto'];
			$idcitas=$_REQUEST['idcitas'];

			$sql="SELECT * from productos
			left outer join productos_catalogo on productos_catalogo.idcatalogo=productos.idcatalogo
			where idproducto=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$idproducto);
			$sth->execute();
			$res=$sth->fetch(PDO::FETCH_OBJ);

			if($producto->tipo==3){
				$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$producto->idproducto'";
				$sth = $db->dbh->prepare($sql);
				$sth->execute();
				$cantidad=$sth->fetch(PDO::FETCH_OBJ);
				$exist=$cantidad->total;
			}
			else{
				$exist=$producto->cantidad;
			}

			echo "<form id='form_producto' action='' data-lugar='a_citas/db_' data-destino='a_citas/editar' data-funcion='agregaventa'>";
			echo "<input type='hidden' name='idcitas' id='idcitas' value='$idcitas' readonly>";
			echo "<input type='hidden' name='idproducto' id='idproducto' value='$idproducto' readonly>";
			echo "<input type='hidden' name='tipo' id='tipo' value='".$res->tipo."' readonly>";
			echo "<div class='row'>";

				echo "<div class='col-12'>";
					echo "<label>Tipo:</label>";
						if($res->tipo=="0") echo " Registro (solo registra ventas, no es necesario registrar entrada)";
						if($res->tipo=="1") echo " Pago de linea";
						if($res->tipo=="2") echo " Reparación";
						if($res->tipo=="3") echo " Volúmen (Se controla el inventario por volúmen)";
						if($res->tipo=="4") echo " Unico (se controla inventario por pieza única)";
					echo "</select>";
				echo "</div>";

				echo "<div class='col-12'>";
					echo "<label>Nombre:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='nombre' id='nombre' value='".$res->nombre."' readonly>";
				echo "</div>";

				if($res->tipo==1 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-3'>";
						echo "<label>Barras</label>";
						echo "<input type='text' class='form-control form-control-sm' name='codigo' id='codigo' value='".$res->codigo."' readonly>";
					echo "</div>";
				}
				if($res->tipo==1 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-3'>";
						echo "<label>Marca</label>";
						echo "<input type='text' class='form-control form-control-sm' name='marca' id='marca' value='".$res->marca."' readonly>";
					echo "</div>";
				}

				if($res->tipo==1 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-3'>";
						echo "<label>Modelo</label>";
						echo "<input type='text' class='form-control form-control-sm' name='modelo' id='modelo' value='".$res->nombre."' readonly>";
					echo "</div>";
				}

				if($res->tipo==1 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-3'>";
						echo "<label>IMEI</label>";
						echo "<input type='text' class='form-control form-control-sm' name='imei' id='imei' value='".$res->imei."' readonly>";
					echo "</div>";
				}
				echo "<div class='col-2'>";
					echo "<label>Existencia:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='existencia' id='existencia' value='".$res->cantidad."' readonly>";
				echo "</div>";
				if($res->tipo==0 or $res->tipo==1 or $res->tipo==2 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-3'>";
						echo "<label>Cantidad</label>";
						echo "<input type='text' class='form-control form-control-sm' name='cantidad' id='cantidad' value='1'";
							if($res->tipo==0 or $res->tipo==2 or $res->tipo==4){
								echo " readonly";
							}
						echo ">";
					echo "</div>";
				}
				if($res->tipo==0 or $res->tipo==1 or $res->tipo==2 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-3'>";
						echo "<label>Precio</label>";
						echo "<input type='text' class='form-control form-control-sm' name='precio' id='precio' value='".$res->precio."' ";
							if($res->tipo==0){
								echo "";
							}
						echo ">";
					echo "</div>";
				}
				if($res->tipo==0 or $res->tipo==1 or $res->tipo==2 or $res->tipo==3 or $res->tipo==4){
					echo "<div class='col-12'>";
						echo "<label>Observaciones</label>";
						echo "<input type='text' class='form-control form-control-sm' name='observaciones' id='observaciones' value='' placeholder='Observaciones'>";
					echo "</div>";
				}
				if($res->tipo==2){
					echo "<div class='col-12'>";
						echo "<label>Cliente:</label>";
						echo "<input type='text' class='form-control form-control-sm' name='cliente' id='cliente' value='' placeholder='Cliente'>";
					echo "</div>";
				}

			echo "</div>";
			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					echo "<div class='btn-group'>";
						echo "<button type='submit' class='btn btn-outline-info btn-sm'><i class='fas fa-cart-plus'></i>Agregar</button>";
						echo "<button type='button' class='btn btn-outline-primary btn-sm' data-dismiss='modal'><i class='fas fa-sign-out-alt'></i>Cerrar</button>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "</form>";

		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function agregaventa(){
		$x="";
		$cliente="";
		$observaciones="";
		$cantidad="0";
		$precio="0";
		$tipo="0";

		$idcitas=$_REQUEST['idcitas'];
		$idproducto=$_REQUEST['idproducto'];
		$cantidad=$_REQUEST['cantidad'];

		$sql="select * from productos where id='$idproducto'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$res=$sth->fetch(PDO::FETCH_OBJ);
		if($res->cantidad<$cantidad){
			$arreglo =array();
			$arreglo+=array('id'=>0);
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"No hay suficientes productos en el inventario");
			return json_encode($arreglo);
		}
		if (isset($_REQUEST['observaciones'])){
			$observaciones=$_REQUEST['observaciones'];
		}
		if (isset($_REQUEST['precio'])){
			$precio=$_REQUEST['precio'];
		}
		if (isset($_REQUEST['cliente'])){
			$cliente=$_REQUEST['cliente'];
		}
		$tipo=$_REQUEST['tipo'];

		try{
			if($tipo<80){
				$sql="SELECT * from productos where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$idproducto);
				$sth->execute();
				$res=$sth->fetch(PDO::FETCH_OBJ);

				////////////////////////////////////////////////////////
				$arreglo=array();
				$arreglo+=array('idcitas'=>$idcitas);
				$arreglo+=array('idproducto'=>$idproducto);
				$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
				$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
				$arreglo+=array('codigo'=>$res->codigo);
				$arreglo+=array('tipo'=>$tipo);
				$arreglo+=array('nombre'=>$res->nombre);
				$arreglo+=array('observaciones'=>$observaciones);
				$arreglo+=array('cliente'=>$cliente);
				if($tipo==3){
					$arreglo+=array('cantidad'=>$cantidad*-1);
				}
				$arreglo+=array('v_cantidad'=>$cantidad);
				$arreglo+=array('v_precio'=>$precio);
				$total=$precio*$cantidad;
				$arreglo+=array('v_total'=>$total);
				if($tipo==4){
					$arreglo+=array('v_marca'=>$res->marca);
					$arreglo+=array('v_modelo'=>$res->modelo);
					$arreglo+=array('v_imei'=>$res->imei);
				}
				//$arreglo+=array('v_total'=>$total);
				$x=$this->insert('bodega', $arreglo);
				$ped=json_decode($x);

				if($ped->error==0){
					{
						$this->total_venta($idcitas);
					}


					///////////////////////////////////////////////////actualiza producto tipo idn_to_unicode
					if($tipo==4){
						$sql="update productos set idcitas=:idcitas where id=:id";
						$sth = $this->dbh->prepare($sql);
						$sth->bindValue(":idcitas",$idcitas);
						$sth->bindValue(":id",$idproducto);
						$sth->execute();
					}

					if($tipo==3){
						/////////////para
					 $this->cantidad_update($idproducto);
					}


					$arreglo =array();
					$arreglo+=array('id'=>$idcitas);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>0);
					$arreglo+=array('param1'=>"");
					$arreglo+=array('param2'=>"");
					$arreglo+=array('param3'=>"");
					return json_encode($arreglo);
				}
				else{
						return $x;
				}
			}
			else{

				$fechac=$_REQUEST['fechac'];
				$horac=$_REQUEST['horac'];
				$estadoc=$_REQUEST['estadoc'];
				$nombrec=$_REQUEST['nombrec'];
				$correoc=$_REQUEST['correoc'];
				$cubiculoc=$_REQUEST['cubiculoc'];
				$atiendec=$_REQUEST['atiendec'];
				$servicioc=$_REQUEST['servicioc'];


				$arreglo=array();
				$arreglo+=array('observaciones'=>$observaciones);

				$nombre=$servicioc;
				$cantidad=1;

				$arreglo=array();
				$arreglo+=array('idcitas'=>$idcitas);
				$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
				$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
				$arreglo+=array('tipo'=>$tipo);
				$arreglo+=array('nombre'=>$nombre);
				$arreglo+=array('observaciones'=>$observaciones);
				$arreglo+=array('cliente'=>$cliente);
				$arreglo+=array('cantidad'=>$cantidad);
				$arreglo+=array('v_cantidad'=>$cantidad);
				$arreglo+=array('v_precio'=>$precio);
				$total=$precio*$cantidad;
				$arreglo+=array('v_total'=>$total);
				$x=$this->insert('bodega', $arreglo);


				$arreglo =array();
				$arreglo+=array('id'=>$idcitas);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>0);
				$arreglo+=array('param1'=>"");
				$arreglo+=array('param2'=>"");
				$arreglo+=array('param3'=>"");
				return json_encode($arreglo);
			}



		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function borrar_venta(){
		$id=$_REQUEST['id'];

		$sql="SELECT * from bodega where id=:id";
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(":id",$id);
		$sth->execute();
		$res=$sth->fetch(PDO::FETCH_OBJ);

		$x=$this->borrar('bodega',"id",$id);

		if($res->tipo==4){
			$sql="update productos set idventa=NULL where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$res->idproducto);
			$sth->execute();
		}

		if($res->tipo==3){
			$this->cantidad_update($res->idproducto);
		}

		$this->total_venta($res->idventa);
		return $x;
	}
}
$db = new Pedidos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
