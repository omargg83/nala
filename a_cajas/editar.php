<?php
	require_once("db_.php");
	if (isset($_REQUEST['idcaja'])){$idcaja=$_REQUEST['idcaja'];} else{ $idcaja=0;}
	$nombrecaja="";

	if($idcaja>0){
		$pd = $db->caja($idcaja);
		$nombrecaja=$pd->nombrecaja;
	}

?>

<div class="container">
		<form is="f-submit" id="form_editar" db="a_cajas/db_" fun="guardar_caja" lug="a_cajas/editar" desid='idcaja'>
		<input type="hidden" name="idcaja" id="idcaja" value="<?php echo $idcaja;?>">
		<div class='card'>
			<div class='card-header'>
				Editar Caja
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-3">
						<label>Nombre Caja:</label>
							<input type="text" class="form-control form-control-sm" name="nombrecaja" id="nombrecaja" value="<?php echo $nombrecaja;?>" placeholder="Nombre" required maxlength="45">
					</div>

				</div>
			</div>
			<div class='card-footer'>
				<div class="row">
					<div class="col-sm-12">

						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link'  des='a_cajas/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
