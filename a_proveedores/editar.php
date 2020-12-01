<?php
	require_once("db_.php");
	if (isset($_REQUEST['idproveedor'])){$idproveedor=clean_var($_REQUEST['idproveedor']);} else{ $idproveedor=0;}
	$nombre="";
	$emailp="";
	$telp="";
	$dirp="";

	if($idproveedor>0){
		$pd = $db->provedor($idproveedor);
		$nombre=$pd->nombre;
		$emailp=$pd->emailp;
		$telp=$pd->telp;
		$dirp=$pd->dirp;
	}

?>

<div class="container">
		<form is="f-submit" id="form_editar" db="a_proveedores/db_" fun="guardar_provedor" lug="a_proveedores/editar" desid='idproveedor'>
		<input type="hidden" name="idproveedor" id="idproveedor" value="<?php echo $idproveedor;?>">
		<div class='card'>
			<div class='card-header'>
				Editar Proveedor
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-xl col-auto">
						<label>Nombre Proveedor:</label>
							<input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre;?>" placeholder="Nombre" required maxlength="100">
					</div>
					<div class="col-xl col-auto">
						<label>Email:</label>
							<input type="text" class="form-control form-control-sm" name="emailp" id="emailp" value="<?php echo $emailp;?>" placeholder="Email" maxlength="100">
					</div>

					<div class="col-xl col-auto">
						<label>Telefonos:</label>
							<input type="text" class="form-control form-control-sm" name="telp" id="telp" value="<?php echo $telp;?>" placeholder="Telefonos" maxlength="45">
					</div>

					<div class="col-xl col-auto">
						<label>Dirección:</label>
							<input type="text" class="form-control form-control-sm" name="dirp" id="dirp" value="<?php echo $dirp;?>" placeholder="Dirección" maxlength="150">
					</div>
				</div>
			</div>
			<div class='card-footer'>
				<div class="row">
					<div class="col-xl col-auto">
						<div class="btn-group">
							<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
							<?php
								if($idproveedor>0){
									echo "<button type='button' class='btn btn-danger btn-sm' is='b-link' db='a_proveedores/db_' des='a_proveedores/lista' fun='borrar_proveedor' dix='trabajo' v_idproveedor='$idproveedor' id='eliminar' tp='¿Desea eliminar el proveedor seleccionado?'><i class='far fa-trash-alt'></i>Eliminar</button>";
								}
							?>
							<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link'  des='a_proveedores/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
					</div>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
