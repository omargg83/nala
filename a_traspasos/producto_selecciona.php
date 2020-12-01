<?php
	require_once("db_.php");
	$idproducto=$_REQUEST['idproducto'];
	$idtraspaso=$_REQUEST['idtraspaso'];

	$sql="SELECT * from productos
	left outer join productos_catalogo on productos_catalogo.idcatalogo=productos.idcatalogo
	where idproducto=:id";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":id",$idproducto);
	$sth->execute();
	$producto=$sth->fetch(PDO::FETCH_OBJ);
	$exist=0;
	if($producto->tipo==3){
		$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$producto->idproducto'";
		$sth = $db->dbh->prepare($sql);
		$sth->execute();
		$cantidad=$sth->fetch(PDO::FETCH_OBJ);
		if(strlen($cantidad->total)>0){
			$exist=$cantidad->total;
		}
		else{
			$exist=0;
		}
	}
	else{
		$exist=$producto->cantidad;
	}

	//////////////////////variables a utilizar para los 2 esquemas que se requieren segun el ejemplo de excel que te mande men
	$producto->esquema;

	$producto->cantidad;
	$producto->precio; //precio unitario * cantidad (menudeo)

	$producto->monto_mayor;
	$producto->monto_distribuidor;

	echo "<form id='form_prod' is='t-selecciona'>";

		echo "<input type='hidden' class='form-control form-control-sm' name='idproducto' id='idproducto' value='$producto->idproducto' readonly>";

		echo "<div class='modal-body' style='max-height:580px;overflow: auto;'>";

			echo "<div class='row'>";
				echo "<div class='col-xl col-auto'>";
					if(strlen($producto->archivo)>0 and file_exists("../".$db->f_productos."/".$producto->archivo)){
						echo "<img src='".$db->f_productos."/".$producto->archivo."' width='100px;' class='img-thumbnail'/>";
					}
					else{
						echo "<img src='img/unnamed.png' width='100px;' class='img-thumbnail'/>";
					}

				echo "</div>";
				echo "<div class='col-xl col-auto'>";
					echo "<label>Producto</label>";
					echo "<input type='text' class='form-control form-control-sm' name='nombre' id='nombre' value='".$producto->nombre."' readonly>";
					echo "<small>";
						if ($producto->esquema==0){
							echo "Este producto no tiene ningun esquema de descuento";
						}
						else if ($producto->esquema==1){
							echo "Esquema Nala";
						}
						else if ($producto->esquema==2){
							echo "Esquema por Cantidad";
						}
					echo "</small>";
					echo "<hr>";
					echo $producto->descripcion;
				echo "</div>";
			echo "</div>";


			echo "<div class='row'>";
				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Cantidad</label>";
					echo "<input type='number' class='form-control form-control-sm' name='cantidad' id='cantidad' value='1' required >";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Existencia:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='existencia' id='existencia' value='$exist' readonly>";
				echo "</div>";
			echo "</div>";

			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-xl col-auto'>";
					echo "<div class='btn-group'>";
						echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fas fa-cart-plus'></i>Agregar</button>";
						echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' cmodal='1' ><i class='fas fa-sign-out-alt'></i>Cerrar</button>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
	echo "</form>";
?>
