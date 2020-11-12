<?php
	require_once("db_.php");
	$idcatalogo=$_REQUEST['idcatalogo'];
	$idcompra=$_REQUEST['idcompra'];

	$sql="SELECT * from productos_catalogo where idcatalogo=:id";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(":id",$idcatalogo);
	$sth->execute();
	$res=$sth->fetch(PDO::FETCH_OBJ);

	echo "<form id='form_productoadd' is='f-submit' db='a_compras/db_' fun='agregacompra' des='a_compras/editar' desid='idcompra' cmodal='2'>";
	echo "<input type='hidden' name='idcompra' id='idcompra' value='$idcompra' readonly>";
	echo "<input type='hidden' name='idcatalogo' id='idcatalogo' value='$idcatalogo' readonly>";
	echo "<input type='hidden' name='tipo' id='tipo' value='".$res->tipo."' readonly>";
	echo "<div class='row'>";

		echo "<div class='col-12'>";
			echo "<label>Tipo:</label>";
				if($res->tipo=="0") echo " Registro (solo registra ventas, no es necesario registrar entrada)";
				if($res->tipo=="3") echo " Volúmen (Se controla el inventario por volúmen)";
			echo "</select>";
		echo "</div>";

		echo "<div class='col-12'>";
			echo "<label>Nombre:</label>";
			echo "<input type='text' class='form-control form-control-sm' name='nombre' id='nombre' value='".$res->nombre."' readonly>";
		echo "</div>";

		echo "<div class='col-3'>";
			echo "<label>Marca</label>";
			echo "<input type='text' class='form-control form-control-sm' name='marca' id='marca' value='".$res->marca."' readonly>";
		echo "</div>";

		echo "<div class='col-3'>";
			echo "<label>Modelo</label>";
			echo "<input type='text' class='form-control form-control-sm' name='modelo' id='modelo' value='".$res->nombre."' readonly>";
		echo "</div>";

		echo "<div class='col-3'>";
			echo "<label>Cantidad</label>";
			echo "<input type='text' class='form-control form-control-sm' name='cantidad' id='cantidad' value='1'>";
		echo "</div>";

		echo "<div class='col-3'>";
			echo "<label>Precio Compra</label>";
			echo "<input type='text' class='form-control form-control-sm' name='precio' id='precio' value='' ";
				if($res->tipo==0){
					echo "";
				}
			echo ">";
		echo "</div>";

		echo "<div class='col-12'>";
			echo "<label>Observaciones</label>";
			echo "<input type='text' class='form-control form-control-sm' name='observaciones' id='observaciones' value='' placeholder='Observaciones'>";
		echo "</div>";

	echo "</div>";
	echo "<hr>";
	echo "<div class='row'>";
		echo "<div class='col-12'>";
			echo "<div class='btn-group'>";
				echo "<button type='submit' class='btn btn-warning btn-sm'><i class='fas fa-cart-plus'></i>Agregar</button>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "</form>";
?>
