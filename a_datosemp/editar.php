<?php
	require_once("db_.php");
	if (isset($_REQUEST['idtienda'])){$idtienda=$_REQUEST['idtienda'];} else{ $idtienda=0;}

	$razon="";
	$calle="";
	$no="";
	$col="";
	$ciudad="";
	$estado="";
	$desglose=0;
	$mensaje="";
	if($idtienda>0){
		$pd = $db->tienda($idtienda);
		$razon=$pd->razon;
		$calle=$pd->calle;
		$no=$pd->no;
		$col=$pd->col;
		$ciudad=$pd->ciudad;
		$estado=$pd->estado;
		$desglose=$pd->desglose;
		$mensaje=$pd->mensaje;
	}

?>

<div class="container">
	<form is="f-submit" id="form_cliente">
		<input type="hidden" name="idtienda" id="idtienda" value="<?php echo $idtienda;?>">
		<div class='card'>
			<div class='card-header'>
				Editar datos empresa
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-3">
						<label>Razón social:</label>
							<input type="text" class="form-control form-control-sm" name="razon" id="razon" value="<?php echo $razon;?>" placeholder="Razón" readonly>
					</div>
					<div class="col-3">
						<label>Calle:</label>
							<input type="text" class="form-control form-control-sm" name="calle" id="calle" value="<?php echo $calle;?>" placeholder="Calle" readonly>
					</div>
					<div class="col-3">
						<label>No.:</label>
							<input type="text" class="form-control form-control-sm" name="no" id="no" value="<?php echo $no;?>" placeholder="Número" required readonly>
					</div>
				</div>
				<div class='row'>
					<div class="col-3">
						<label>Colonia:</label>
							<input type="text" class="form-control form-control-sm" name="col" id="col" value="<?php echo $col;?>" placeholder="Colonia" readonly>
					</div>
					<div class="col-3">
						<label>Ciudad:</label>
							<input type="text" class="form-control form-control-sm" name="ciudad" id="ciudad" value="<?php echo $ciudad;?>" placeholder="Ciudad" readonly>
					</div>
					<div class="col-3">
						<label>Estado:</label>
							<input type="text" class="form-control form-control-sm" name="estado" id="estado" value="<?php echo $estado;?>" placeholder="Estado" readonly>
					</div>
				</div>

				<div class='row'>
					<div class='col-3'>
						<label>Desglose de iva</label>
						<select class="form-control form-control-sm" name="desglose" id="desglose"required>
							<option value='0'<?php if($desglose=='0') echo 'selected'; ?> >NO</option>
							<option value='1'<?php if($desglose=='1') echo 'selected'; ?> >SI</option>
						</select>
					</div>
					<div class="col-3">
						<label>Mensaje ticket</label>
							<input type="text" class="form-control form-control-sm" name="mensaje" id="mensaje" value="<?php echo $mensaje;?>" placeholder="Mensaje del ticket" readonly>
					</div>

				</div>

			</div>
			<div class='card-footer'>
				<div class="row">
					<div class="col-sm-12">
						<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link' des='a_datosemp/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
