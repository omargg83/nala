<?php
	require_once("db_.php");

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->cajas_buscar($texto);
	}
	else{
		$pd = $db->cajas_lista();
	}
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>


<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE CAJAS EN LA SUCURSAL
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-2'>NOMBRE</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-2'>";
						echo "<div class='btn-group'>";

						echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_cajas/editar' dix='trabajo' v_idcaja='$key->idcaja'><i class='fas fa-pencil-alt'></i></button>";

						echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_cajas/db_' des='a_cajas/lista' fun='borrar_caja' dix='trabajo' v_idcaja='$key->idcaja' id='eliminar' tp='¿Desea eliminar la caja seleccionada?'><i class='far fa-trash-alt'></i></button>";

						echo "</div>";
					echo "</div>";

					echo "<div class='col-2'>".$key->nombrecaja."</div>";
				echo "</div>";
			}
		?>
	</div>
</div>
