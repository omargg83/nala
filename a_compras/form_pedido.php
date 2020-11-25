<?php
	require_once("db_.php");

	$idcompra = $_REQUEST['idcompra'];
	$pedido = $db->entrada($idcompra);
	echo "<div class='tabla_css' id='tabla_css'>";
		echo "<div class='row header-row'>";
			echo "<div class='col-2'>#</div>";
			echo "<div class='col-2'>Código</div>";
			echo "<div class='col-2'>Nombre</div>";
			echo "<div class='col-2'>Cantidad</div>";
			echo "<div class='col-2'>Precio Compra</div>";
			echo "<div class='col-2'>Total</div>";
		echo "</div>";

	$gtotal=0;
	$idpaquete=0;
	$contar=1;
	$estado=$pd->estado;
	$suma=0;
	foreach($pedido as $key){
		echo "<div class='row body-row' draggable='true'>";
			echo "<div class='col-2'>";

		if($estado=="Activa" and $key->cantidad>0){

			echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_compras/db_' des='a_compras/editar' v_idcompra='$idcompra' fun='borrar_registro' dix='trabajo' v_idbodega='$key->idbodega' id='eliminar' tp='¿Desea eliminar el registro seleccionado?'><i class='far fa-trash-alt'></i></button>";

		}

		echo "</div>";
		echo "<div class='col-2'>".$key->codigo."</div>";
		echo "<div class='col-2'>".$key->nombre."</div>";
		echo "<div class='col-2 text-center'>".$key->cantidad."</div>";
		echo "<div class='col-2 text-right'>".moneda($key->c_precio)."</div>";
		echo "<div class='col-2 text-right'>".moneda($key->cantidad*$key->c_precio)."</div>";
		$suma+=$key->cantidad*$key->c_precio;
		echo "</div>";
	}
	echo "</div>";
	echo "<div class='row body-row' draggable='true'>";
		echo "<div class='col-10'>";
		echo "</div>";
		echo "<div class='col-2 text-right'>";
			echo "<h4>";
			echo moneda($suma);
			echo "</h4>";
		echo "</div>";
	echo "</div>";
?>
