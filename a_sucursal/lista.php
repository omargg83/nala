<?php
	require_once("db_.php");
	$pd = $db->sucursal_lista();
	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
?>
<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-xl col-auto'>
			SUCURSAL
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-xl col-auto'>#</div>
		<div class='col-xl col-auto'>Nombre</div>
		<div class='col-xl col-auto'>Ubicación</div>
		<div class='col-xl col-auto'>Ciudad</div>
		<div class='col-xl col-auto'>Teléfono 1</div>
		<div class='col-xl col-auto'>Teléfono 2</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-xl col-auto text-center'>";
					echo "<div class='btn-group'>";

					echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_sucursal/editar' dix='trabajo' v_idsucursal='$key->idsucursal'><i class='fas fa-pencil-alt'></i></button>";

					echo "</div>";
					echo "</div>";
					echo "<div class='col-xl col-auto'>".$key->nombre."</div>";
					echo "<div class='col-xl col-auto'>".$key->ubicacion."</div>";
					echo "<div class='col-xl col-auto'>".$key->ciudad."</div>";
					echo "<div class='col-xl col-auto'>".$key->tel1."</div>";
					echo "<div class='col-xl col-auto'>".$key->tel2."</div>";
				echo "</div>";
			}
		?>
	</div>
	</tbody>
	</table>
</div>
