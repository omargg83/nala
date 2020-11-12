<?php
  require_once("db_.php");

  $idtienda=$_REQUEST['idtienda'];
	$pd = $db->usuario_lista($idtienda);
  $tienda = $db->tienda($idtienda);
	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
?>

		<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-12'>
        <br>
				LISTA DE USUARIOS:
        <?php
          echo $tienda->nombre_sis;
        ?>
        <hr>
        <?php
        echo "<button class='btn btn-warning btn-sm' is='b-link' des='a_usuarios/editar' dix='trabajo' v_idusuario='0' v_idtienda='$idtienda' id='edit_persona'><i class='far fa-plus-square'></i>Nuevo</button>";

        echo "<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link' des='a_empresas/lista' v_idtienda='$idtienda' dix='trabajo'><i class='fas fa-undo-alt'></i>Regresar</button>";
        ?>
        <hr>
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

								echo "<button class='btn btn-warning btn-sm' is='b-link' des='a_usuarios/editar' dix='trabajo' v_idusuario='$key->idusuario' v_idtienda='$idtienda' id='edit_persona'><i class='fas fa-pencil-alt'></i></button>";

								echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_usuarios/db_' des='a_usuarios/lista' des='idtienda' fun='borrar_usuario' dix='trabajo' v_id='$key->idusuario'  v_idtienda='$idtienda' id='eliminar' tp='¿Desea eliminar el usuario seleccionado?'><i class='far fa-trash-alt'></i></button>";

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
