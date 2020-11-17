<?php
	require_once("db_.php");

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->ventas_buscar($texto);
	}
	else{
		$pd = $db->ventas_pagadas();
	}

	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>
<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE VENTAS EFECTUADAS
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-2'>Numero</div>
		<div class='col-2'>Fecha</div>
		<div class='col-2'>Cliente</div>
		<div class='col-2'>Total</div>
		<div class='col-2'>Estado</div>
	</div>

		<?php
			foreach($pd as $key){
		?>
				<div class='row body-row' draggable='true'>
					<div class='col-2'>
						<div class="btn-group">
							<button class='btn btn-warning btn-sm'  id='edit_persona' is='b-link' id='nueva_venta' des='a_venta/venta' dix='trabajo'  v_idventa='<?php echo $key->idventa; ?> ' ><i class="fas fa-pencil-alt"></i></button>
						</div>
					</div>
					<div class='col-2'><?php echo $key->numero; ?></div>
					<div class='col-2'><?php echo fecha($key->fecha); ?></div>
					<div class='col-2'><?php echo $key->nombre; ?></div>

					<div class='col-2' align="center">$ <?php echo number_format($key->total,2); ?></div>
					<div class='col-2'><?php echo $key->estado; ?></div>

				</div>
		<?php
			}
		?>
		</tbody>
	</table>
</div>
