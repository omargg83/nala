<?php
	require_once("db_.php");
	$idcatalogo=$_REQUEST['idcatalogo'];
	$idcompra=$_REQUEST['idcompra'];

	$precio=0;

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
		echo "<div class='col-xl col-auto'>";
			if(strlen($res->archivo)>0 and file_exists("../".$db->f_productos."/".$res->archivo)){
				echo "<img src='".$db->f_productos."/".$res->archivo."' width='100%' class='img-thumbnail'/>";
			}
			else{
				echo "<img src='img/unnamed.png' width='100%' class='img-thumbnail'/>";
			}
		echo "</div>";
		echo "<div class='col-xl col-auto'>";
			echo "<div class='row'>";

				echo "<div class='col-xl col-auto'>";
					echo "<b>$res->nombre</b></br>";
					echo "<small>";
							if($res->tipo=="0") echo " Registro (solo registra ventas, no es necesario registrar entrada)";
							if($res->tipo=="3") echo " Volúmen (Se controla el inventario por volúmen)";
					echo "</small>";
					echo "<p><b>$res->descripcion</b></p>";

				echo "</div>";
			echo "</div>";
				echo "<div class='col-xl col-auto'>";
					echo "<label>Cantidad</label>";
					echo "<input type='text' class='form-control form-control-sm' name='cantidad' id='cantidad' value='1'>";
				echo "</div>";

				echo "<div class='col-xl col-auto'>";
					echo "<label>Precio Compra</label>";
					echo "<input type='text' class='form-control form-control-sm' name='precio' id='precio' value='$precio' ";
						if($res->tipo==0){
							echo "";
						}
					echo ">";
				echo "</div>";

				echo "<div class='col-xl col-auto'>";
					echo "<label>Observaciones</label>";
					echo "<input type='text' class='form-control form-control-sm' name='observaciones' id='observaciones' value='' placeholder='Observaciones'>";
				echo "</div>";

			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "<div class='row'>";
		echo "<div class='col-xl col-auto'>";
			echo "<div class='btn-group'>";
				echo "<button type='submit' class='btn btn-warning btn-sm'><i class='fas fa-cart-plus'></i>Agregar</button>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "</form>";
?>
