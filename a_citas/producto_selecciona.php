<?php
	require_once("db_.php");
	$idproducto=$_REQUEST['idproducto'];

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
	$producto->esquema; // definira el tipo de esquema (agregue este campo apenas a la bdd men) es entero y nos servira para definir o agregar futuros esquemas de descuento por producto

	$producto->cantidad;
	$producto->precio; //precio unitario * cantidad (menudeo)
	$producto->preciom; // precio mayoreo * Cantidad
	$producto->preciod; // precio distribuidor * Cantidad


	// $parametro1 digamos los 1000 pesos  para llegar a mayoreo (esquema 1 NALA) tomar en cuenta que hoy son mil y mañana podria cambiar, lo mismo para elk parametro2
	// $parametro2 los 3 mil pesos para llegar a distribuidor    (esquema 1 NALA)
	/////////////////////
	echo "<form id='form_prod' is='is-selecciona' v_idproducto='$idproducto'>";

		echo "<div class='modal-header'>";
	  	echo "<h5 class='modal-title'>Agregar producto</h5>";
	  echo "</div>";

		echo "<div class='modal-body' style='max-height:580px;overflow: auto;'>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					echo "<input type='text' class='form-control form-control-sm' name='nombre' id='nombre' value='".$producto->nombre."' readonly>";

					echo "<small>";
						echo $producto->nombre;
					echo "</small>";
					echo "<hr>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Existencia:</label>";
					echo "<input type='text' class='form-control form-control-sm' name='existencia' id='existencia' value='$exist' readonly>";
				echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Cantidad</label>";
					echo "<input type='text' class='form-control form-control-sm' name='cantidad' id='cantidad' value='1' required>";
				echo "</div>";

				echo "<div class='col-sm-12 col-md-12 col-lg-4 col-xl-4'>";
					echo "<label>Precio</label>";
					echo "<input type='text' class='form-control form-control-sm' name='precio' id='precio' value='".$producto->precio."' required>";
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
