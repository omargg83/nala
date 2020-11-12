<?php
	require_once("db_.php");
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->usuario_buscar($texto);
	}
	else{
		$pd = $db->usuario_lista();
	}
	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
?>
<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE USUARIOS
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-4'>NOMBRE</div>
		<div class='col-2'>NIVEL</div>
		<div class='col-2'>ACTIVO</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
						echo "<div class='col-2'>";

							echo "<button class='btn btn-warning btn-sm' is='b-link' des='a_usuarios/editar' dix='trabajo' v_id='$key->idusuario' id='edit_persona'><i class='fas fa-pencil-alt'></i></button>";
							echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_usuarios/db_' des='a_usuarios/lista' fun='borrar_usuario' dix='trabajo' v_id='$key->idusuario' id='eliminar' tp='¿Desea eliminar el usuario seleccionado?'><i class='far fa-trash-alt'></i></button>";
							
						echo "</div>";
					echo "<div class='col-4'>".$key->nombre."</div>";
					echo "<div class='col-2'>".$key->nivel."</div>";
					echo "<div class='col-2'>";
					if ($key->activo==0) { echo "Inactivo"; }
					if ($key->activo==1) { echo "Activo"; }
					echo "</div>";
				echo "</div>";
			}
		?>
	</tbody>
	</table>
</div>
