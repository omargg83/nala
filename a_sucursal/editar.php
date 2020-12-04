<?php
	require_once("db_.php");
	if (isset($_REQUEST['idsucursal'])){$idsucursal=$_REQUEST['idsucursal'];} else{ $idsucursal=0;}

	$nombre="";
	$ubicacion="";
	$ciudad="";
	$tel1="";
	$tel2="";
	$cp="";
	$estado="";
	$tipoticket="";
	$etiqueta="";
	$matriz="";
	if($idsucursal>0){
		$pd = $db->sucursal($idsucursal);
		$nombre=$pd->nombre;
		$ubicacion=$pd->ubicacion;
		$ciudad=$pd->ciudad;
		$tel1=$pd->tel1;
		$tel2=$pd->tel2;
		$cp=$pd->cp;
		$estado=$pd->estado;
		$tipoticket=$pd->tipoticket;
		$etiqueta=$pd->etiqueta;
		$matriz=$pd->matriz;
	}
?>

<div class="container">
	<form is="f-submit" id="form_cliente" db="a_sucursal/db_" fun="guardar_sucursal" des="a_sucursal/editar" desid='idsucursal'>
		<input type="hidden" name="idsucursal" id="idsucursal" value="<?php echo $idsucursal;?>">
		<div class='card'>
			<div class='card-header'>
				Editar sucursal
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-xl col-auto">
						<label>Nombre:</label>
							<input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre;?>" placeholder="Nombre" required maxlength='100'>
					</div>
					<div class="col-xl col-auto">
						<label>Ubicación:</label>
							<input type="text" class="form-control form-control-sm" name="ubicacion" id="ubicacion" value="<?php echo $ubicacion;?>" placeholder="Ubicación" required maxlength='155'>
					</div>
					<div class="col-xl col-auto">
						<label>Ciudad:</label>
							<input type="text" class="form-control form-control-sm" name="ciudad" id="ciudad" value="<?php echo $ciudad;?>" placeholder="Ciudad" required maxlength='145'>
					</div>
				</div>
				<div class='row'>
						<div class="col-xl col-auto">
							<label>Estado:</label>
								<input type="text" class="form-control form-control-sm" name="estado" id="estado" value="<?php echo $estado;?>" placeholder="Estado" required maxlength='45'>
						</div>

						<div class="col-xl col-auto">
							<label>Código Postal:</label>
								<input type="text" class="form-control form-control-sm" name="cp" id="cp" value="<?php echo $cp;?>" placeholder="Código  postal" maxlength='5'>
						</div>
						<div class="col-xl col-auto">
							<label>Teléfono 1:</label>
								<input type="text" class="form-control form-control-sm" name="tel1" id="tel1" value="<?php echo $tel1;?>" placeholder="Teléfono 1" maxlength='15'>
						</div>
				</div>
				<div class='row'>
					<div class="col-xl col-auto">
						<label>Teléfono 2:</label>
							<input type="text" class="form-control form-control-sm" name="tel2" id="tel2" value="<?php echo $tel2;?>" placeholder="Teléfono 2" maxlength='15'>
					</div>

					<div class='col-xl col-auto'>
						<label>Tamaño de ticket de venta:</label>
						<select class="form-control form-control-sm" name="tipoticket" id="tipoticket"required>
							<option value='0'<?php if($tipoticket=='0') echo 'selected'; ?> >58mm</option>
							<option value='1'<?php if($tipoticket=='1') echo 'selected'; ?> >80mm</option>
						</select>
					</div>

					<div class='col-xl col-auto'>
						<label>Tamaño de papel para etiqueta:</label>
						<select class="form-control form-control-sm" name="etiqueta" id="etiqueta"required>
							<option value='0'<?php if($etiqueta=='0') echo 'selected'; ?> >Carta</option>
							<option value='1'<?php if($etiqueta=='1') echo 'selected'; ?> >58mm</option>
							<option value='2'<?php if($etiqueta=='2') echo 'selected'; ?> >80mm</option>
						</select>
					</div>

					<div class='col-xl col-auto'>
						<label>Matriz:</label>
						<select class="form-control form-control-sm" name="matriz" id="matriz" required>
							<option value='1'<?php if($matriz=='1') echo 'selected'; ?> >Si</option>
							<option value='0'<?php if($matriz=='0') echo 'selected'; ?> >No</option>
						</select>
					</div>
				</div>
			</div>

			<div class='card-footer'>
				<div class="row">
					<div class="col-xl col-auto">
						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link' des='a_sucursal/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
