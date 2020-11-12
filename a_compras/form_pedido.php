<?php
	require_once("db_.php");

	$idcompra = $_REQUEST['idcompra'];
	$pedido = $db->entrada($idcompra);

	echo "<table class='table table-sm'>";
	echo "<tr>
	<th>-</th>
	<th>Código</th>
	<th>Nombre</th>
	<th>Cantidad</th>
	<th>Precio Compra</th>
	<th>Total</th>
	</tr>";
	$gtotal=0;
	$idpaquete=0;
	$contar=1;
	$estado=$pd->estado;
	$suma=0;
	foreach($pedido as $key){
		echo "<tr id='".$key->idbodega."' class='edit-t'>";
		echo "<td>";
		if($estado=="Activa" and $key->cantidad>0){

			echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_compras/db_' des='a_compras/editar' v_idcompra='$idcompra' fun='borrar_registro' dix='trabajo' v_idbodega='$key->idbodega' id='eliminar' tp='¿Desea eliminar el registro seleccionado?'><i class='far fa-trash-alt'></i></button>";

		}

		echo "</td>";
		echo "<td>".$key->codigo."</td>";
		echo "<td>".$key->nombre."</td>";
		echo "<td align='right'>".$key->cantidad."</td>";
		echo "<td align='right'>".moneda($key->c_precio)."</td>";
		echo "<td align='right'>".moneda($key->cantidad*$key->c_precio)."</td>";
		$suma+=$key->cantidad*$key->c_precio;
		echo "</tr>";
	}
	echo "total".moneda($suma);
?>
