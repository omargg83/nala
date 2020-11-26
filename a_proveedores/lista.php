<?php
	require_once("db_.php");

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->provedores_buscar($texto);
	}
	else{
		$pd = $db->provedores_lista();
	}
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>


<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE PROVEEDORES
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-2'>NOMBRE</div>
		<div class='col-2'>EMAIL</div>
		<div class='col-2'>TELEFONO</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-2'>";
						echo "<div class='btn-group'>";

						if($db->nivel_captura==1){
							echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_proveedores/editar' dix='trabajo' v_idproveedor='$key->idproveedor'><i class='fas fa-pencil-alt'></i></button>";
							echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_proveedores/db_' des='a_proveedores/lista' fun='borrar_proveedor' dix='trabajo' v_idproveedor='$key->idproveedor' id='eliminar' tp='¿Desea eliminar el proveedor seleccionado?'><i class='far fa-trash-alt'></i></button>";
							}		


						echo "</div>";
					echo "</div>";

					echo "<div class='col-2'>".$key->nombre."</div>";
					echo "<div class='col-2'>".$key->emailp."</div>";
					echo "<div class='col-2'>".$key->telp."</div>";
				echo "</div>";
			}
		?>
	</div>
</div>
