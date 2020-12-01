<?php
	require_once("db_.php");
	$pd = $db->tienda_lista();
	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
?>
<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-xl col-auto'>
			DATOS DE LA EMPRESA
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-xl col-auto'>#</div>
		<div class='col-xl col-auto'>Razon Social</div>
		<div class='col-xl col-auto'>Calle</div>
		<div class='col-xl col-auto'>No</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-xl col-auto'>";
					echo "<div class='btn-group'>";
					echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_datosemp/editar' dix='trabajo' v_idtienda='$key->idtienda'><i class='fas fa-pencil-alt'></i></button>";
					echo "</div>";
					echo "</div>";
					echo "<div class='col-xl col-auto text-center'>".$key->razon."</div>";
					echo "<div class='col-xl col-auto text-center'>".$key->calle."</div>";
					echo "<div class='col-xl col-auto text-center'>".$key->no."</div>";
				echo "</div>";
			}
		?>
	</div>
	</tbody>
	</table>
</div>
