<?php
	require_once("db_.php");

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->clientes_buscar($texto);
	}
	else{
		$pd = $db->clientes_lista();
	}
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>

<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE CLIENTES
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-1'>#</div>
		<div class='col-2'>RFC</div>
		<div class='col-2'>Razon Social</div>
		<div class='col-3'>Nombre</div>
		<div class='col-2'>Correo</div>
		<div class='col-2'>Telefono</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
						echo "<div class='col-1'>";
							echo "<div class='btn-group'>";
							if($db->nivel_captura==1){
								echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_cliente/editar' dix='trabajo' v_id='$key->idcliente'><i class='fas fa-pencil-alt'></i></button>";
								echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_cliente/db_' des='a_cliente/lista' fun='borrar_cliente' dix='trabajo' v_id='$key->idcliente' id='eliminar' tp='¿Desea eliminar el cliente seleccionado?'><i class='far fa-trash-alt'></i></button>";
			
							}

						echo "</div>";
					echo "</div>";

					echo "<div class='col-2'>".$key->rfc."</div>";
					echo "<div class='col-2'>".$key->razon_social."</div>";
					echo "<div class='col-3'>".$key->nombre."</div>";
					echo "<div class='col-2'>".$key->correo."</div>";
					echo "<div class='col-2'>".$key->telefono."</div>";



				echo "</div>";
			}
		?>
	</div>
