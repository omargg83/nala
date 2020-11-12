<?php
	require_once("db_.php");
	if (isset($_REQUEST['idcat'])){$idcat=$_REQUEST['idcat'];} else{ $idcat=0;}

	$nombre="";


	if($idcat>0){
		$pd = $db->categoria($idcat);
		$nombre=$pd->nombre;

	}

?>

<div class="container">
		<form is="f-submit" id="form_editar" db="a_categorias/db_" fun="guardar_categoria" lug="a_categorias/editar" desid='idcat'>
		<input type="hidden" name="idcat" id="idcat" value="<?php echo $idcat;?>">
		<div class='card'>
			<div class='card-header'>
				Administrar categorías
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-6">
						<label>Nombre categoría:</label>
							<input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre;?>" placeholder="Nombre" required maxlength="55">
					</div>

				</div>
			</div>
			<div class='card-footer'>
				<div class="row">
					<div class="col-sm-12">

						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link'  des='a_categorias/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
