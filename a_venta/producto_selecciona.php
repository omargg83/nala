<?php
	require_once("db_.php");
	$idproducto=$_REQUEST['idproducto'];
	$idventa=$_REQUEST['idventa'];

	$sql="SELECT * from productos
	left outer join productos_catalogo on productos_catalogo.idcatalogo=productos.idcatalogo
	where idproducto=:id";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":id",$idproducto);
	$sth->execute();
	$producto=$sth->fetch(PDO::FETCH_OBJ);

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

	//////////////////////variables a utilizar para los 2 esquemas que se requieren segun el ejemplo de excel que te mande men
	$producto->esquema;

	$producto->cantidad;
	$producto->precio; //precio unitario * cantidad (menudeo)

	$producto->monto_mayor;
	$producto->monto_distribuidor;

	echo "<form id='form_prod' is='is-selecciona'>";

		echo "<input type='hidden' class='form-control form-control-sm' name='idproducto' id='idproducto' value='$producto->idproducto' readonly>";

		echo "<div class='modal-header'>";
	  	echo "<h5 class='modal-title'>Agregar producto</h5>";
	  echo "</div>";

		echo "<div class='modal-body' style='max-height:580px;overflow: auto;'>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
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
					echo "<hr>";
				echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label><b>Cantidad</b></label>";
					echo "<input type='number' min=0 max=9999 class='form-control' is='f-cantidad' name='cantidad' id='cantidad' value='1' required>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label><b>Precio a aplicar x Unidad</b></label>";
					echo "<input type='text' class='form-control' name='precio' id='precio' value='".$producto->precio."' readonly>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label><b>Existencia</b>:</label>";
					echo "<input type='text' class='form-control' name='existencia' id='existencia' value='$exist' readonly>";
				echo "</div>";

			echo "</div>";

			echo "<hr>";
				//////////////////////arreglar esto
			echo "<div class='row'>";
				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Precio normal</label>";
					echo "<input type='text' class='form-control form-control-sm' name='precio_normal' id='precio_normal' value='$producto->precio' readonly>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Precio Mayoreo</label>";
					echo "<input type='text' class='form-control form-control-sm' name='precio_mayoreo' id='precio_mayoreo' value='$producto->precio_mayoreo' readonly>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Precio Ditribuidor</label>";
					echo "<input type='text' class='form-control form-control-sm' name='precio_distribuidor' id='precio_distribuidor' value='$producto->precio_distri' readonly>";
				echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Total normal</label>";
					echo "<input type='text' class='form-control form-control-sm' name='normal' id='normal' value='".$producto->precio."' readonly>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Total Mayoreo</label>";
					echo "<input type='text' class='form-control form-control-sm' name='mayoreo' id='mayoreo' value='".$producto->precio_mayoreo."' readonly>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Total Ditribuidor</label>";
					echo "<input type='text' class='form-control form-control-sm' name='distribuidor' id='distribuidor' value='".$producto->precio_distri."' readonly>";
				echo "</div>";
			echo "</div>";

			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
						echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fas fa-cart-plus'></i>Agregar</button>";
						echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' cmodal='1' ><i class='fas fa-sign-out-alt'></i>Cancelar</button>";
				echo "</div>";
			echo "</div>";
	echo "</form>";
?>
