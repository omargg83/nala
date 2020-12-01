<?php
	require_once("db_.php");

	$pd = $db->duplicados_lista();

	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>

	<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-xl col-auto'>
				INVENTARIO DE PRODUCTOS
			</div>
		</div>
		<div class='row header-row'>
			<div class='col-xl col-auto'>#</div>
			<div class='col-xl col-auto'>Existencias</div>
			<div class='col-xl col-auto'>Código</div>
			<div class='col-xl col-auto'>idcatalogo</div>
			<div class='col-xl col-auto'>Nombre</div>
			<div class='col-xl col-auto'>Existencia</div>
			<div class='col-xl col-auto'>Precio de venta</div>
		</div>

			<?php
				foreach($pd as $key){
					echo "<div class='row body-row' draggable='true'>";
						echo "<div class='col-xl col-auto'>";
							echo "<div class='btn-group'>";

              if($key->duplicados>1){
							   echo "<button type='button' class='btn btn-danger btn-sm' id='edit_persona'><i class='fas fa-pencil-alt'></i>HOMOLOGAR</button>";
              }

							echo "</div>";
						echo "</div>";

						echo "<div class='col-xl col-auto'>";
							echo $key->duplicados;
						echo "</div>";

						echo "<div class='col-xl col-auto'>";
							echo $key->codigo;
						echo "</div>";

						echo "<div class='col-xl col-auto'>";
							echo $key->idcatalogo;
						echo "</div>";

						echo "<div class='col-xl col-auto'>".$key->nombre."</div>";

						echo "<div class='col-xl col-auto text-center' >".moneda($key->precio)."</div>";
					echo '</div>';
				}
			?>
		</div>
	</div>
