<?php
	require_once("db_.php");

	if(isset($_REQUEST['idsucursal'])){
		$idsucursal=$_REQUEST['idsucursal'];
	}
	else{
		$idsucursal=$_SESSION['idsucursal'];
	}

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->producto_buscar($texto);
	}
	else{
		$pd = $db->productos_lista();
	}
	$sucursal=$db->sucursal();

	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>

<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE PRODUCTOS
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-2'>Tipo</div>
		<div class='col-4'>Nombre</div>
		<div class='col-4'>Descripción</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-2'>";
						echo "<div class='btn-group'>";

						echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_productos/editar' dix='trabajo' v_idcatalogo='$key->idcatalogo'><i class='fas fa-pencil-alt'></i></button>";
						
						echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_productos/db_' des='a_productos/lista' fun='borrar_producto' dix='trabajo' v_idcatalogo='$key->idcatalogo' id='eliminar' tp='¿Desea eliminar el Producto seleccionado?'><i class='far fa-trash-alt'></i></button>";

						echo "</div>";
					echo "</div>";

					echo "<div class='col-2'>";
						if($key->tipo==0) echo "Servicio";
						if($key->tipo==3) echo "Producto";
					echo "</div>";

					echo "<div class='col-4'>".$key->nombre."</div>";

					echo "<div class='col-4'>".$key->descripcion."</div>";


				echo '</div>';
			}
		?>

	</div>
