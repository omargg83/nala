<?php
	require_once("db_.php");

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->traspasos_buscar($texto);
	}
	else{
		$pd = $db->traspasos_lista();
	}

	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>
	<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-12'>
				LISTA DE ENVIOS
			</div>
		</div>
		<div class='row header-row'>
			<div class='col-2'>#</div>
			<div class='col-2'>Numero</div>
			<div class='col-2'>Fecha</div>
			<div class='col-2'>nombre</div>
			<div class='col-2'>Estado</div>
			<div class='col-2'>Destino</div>
		</div>

			<?php
				foreach($pd as $key){
			?>
					<div class='row body-row' draggable='true'>
						<div class='col-2'>
							<div class="btn-group">
								<button class='btn btn-warning btn-sm'  id='edit_persona' is='b-link' id='nueva_venta' des='a_traspasos/editar' dix='trabajo'  v_idtraspaso='<?php echo $key->idtraspaso; ?>' ><i class="fas fa-pencil-alt"></i></button>
							</div>
						</div>
						<div class='col-2'><?php echo $key->numero; ?></div>
						<div class='col-2'><?php echo fecha($key->fecha); ?></div>
						<div class='col-2'><?php echo $key->nombre; ?></div>

						<div class='col-2'><?php echo $key->estado; ?></div>
						<div class='col-2'><?php echo $key->idsucursal; ?></div>

					</div>
			<?php
				}
			?>
	</div>
	</div>
<br>

<?php
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->recepcion_buscar($texto);
	}
	else{
		$pd = $db->recepcion_lista();
	}


	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>
<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE RECEPCION
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-2'>Numero</div>
		<div class='col-2'>Fecha</div>
		<div class='col-2'>nombre</div>
		<div class='col-2'>Estado</div>
		<div class='col-2'>Destino</div>
	</div>

		<?php
			foreach($pd as $key){
		?>
				<div class='row body-row' draggable='true'>
					<div class='col-2'>
						<div class="btn-group">
							<button class='btn btn-warning btn-sm'  id='edit_persona' is='b-link' id='nueva_venta' des='a_traspasos/editar' dix='trabajo'  v_idtraspaso='<?php echo $key->idtraspaso; ?>' ><i class="fas fa-pencil-alt"></i></button>
						</div>
					</div>
					<div class='col-2'><?php echo $key->numero; ?></div>
					<div class='col-2'><?php echo fecha($key->fecha); ?></div>
					<div class='col-2'><?php echo $key->nombre; ?></div>

					<div class='col-2'><?php echo $key->estado; ?></div>
					<div class='col-2'><?php echo $key->idsucursal; ?></div>

				</div>
		<?php
			}
		?>
		</tbody>
	</table>
</div>
