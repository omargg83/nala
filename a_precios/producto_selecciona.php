<?php
	require_once("db_.php");
	$idproducto=$_REQUEST['idproducto'];
	$idventa=$_REQUEST['idventa'];
	echo "idventa:".$idventa;

	$sql="SELECT * from productos where idproducto=:id";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":id",$idproducto);
	$sth->execute();
	$producto=$sth->fetch(PDO::FETCH_OBJ);

	$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$producto->idproducto'";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$cantidad=$sth->fetch(PDO::FETCH_OBJ);


	echo "<div class='modal-body' style='max-height:580px;overflow: auto;'>";

	echo "<form id='form_prod' is='is-selecciona' v_idproducto='$idproducto'>";

	echo "<div class='row'>";

		echo "<div class='col-12 col-xl col-auto'>";
			echo "<label>Nombre:</label>";
			echo "<input type='text' class='form-control' name='nombre' id='nombre' value='".$producto->nombre."' readonly>";
		echo "</div>";

		echo "<div class='col-12 col-xl col-auto'>";
			echo "<label>Existencia:</label>";
			echo "<input type='text' class='form-control' name='existencia' id='existencia' value='".$cantidad->total."' readonly>";
		echo "</div>";

		echo "<div class='col-12 col-xl col-auto'>";
			echo "<label>Cantidad</label>";
			echo "<input type='text' class='form-control' name='cantidad' id='cantidad' value='1'";

			echo ">";
		echo "</div>";

		echo "<div class='col-12 col-xl col-auto'>";
			echo "<label>Precio</label>";
			echo "<input type='text' class='form-control' name='precio' id='precio' value='".$producto->precio."' ";
				echo ">";
		echo "</div>";

	echo "</div>";
	echo "<hr>";
	echo "<div class='row'>";
		echo "<div class='col-12 col-xl col-auto'>";
			echo "<div class='btn-group'>";
				echo "<button type='submit' class='btn btn-warning btn-sm'><i class='fas fa-cart-plus'></i>Agregar</button>";
				echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' cmodal='1' ><i class='fas fa-sign-out-alt'></i>Cancelar</button>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "</form>";
?>
