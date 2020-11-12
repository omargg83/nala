<?php
	require_once("db_.php");
	if (isset($_POST['id'])){$id=$_POST['id'];} else{ $id=0;}

	$razon_social="";
	$nombre="";
	$rfc="";
	$telefono="";
	$correo="";
	$profesion="";
	$cfdi="";
	$entrecalles="";
	$direccion="";
	$numero="";
	$colonia="";
	$ciudad="";
	$cp="";
	$pais="";
	$estado="";
	$observaciones="";

	if($id>0){
		$pd = $db->cliente($id);
		$razon_social=$pd->razon_social;
		$nombre=$pd->nombre;
		$rfc=$pd->rfc;
		$telefono=$pd->telefono;
		$correo=$pd->correo;
		$cfdi=$pd->cfdi;
		$entrecalles=$pd->entrecalles;
		$direccion=$pd->direccion;
		$numero=$pd->numero;
		$colonia=$pd->colonia;
		$ciudad=$pd->ciudad;
		$cp=$pd->cp;
		$pais=$pd->pais;
		$estado=$pd->estado;
		$observaciones=$pd->observaciones;
	}

?>

<div class="container">
		<form is="f-submit" id="form_editar" db="a_cliente/db_" fun="guardar_cliente" lug="a_cliente/editar" desid='idcliente'>
		<input type="hidden" name="idcliente" id="idcliente" value="<?php echo $id;?>">
		<div class='card'>
			<div class='card-header'>
				Editar cliente
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-12">
						<label>Razon social:</label>
							<input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" value="<?php echo $razon_social;?>" maxlength='100' placeholder="Razon social" >
					</div>
					<div class="col-12">
						<label>Uso cfdi</label>
						<input type="text" class="form-control form-control-sm" id="cfdi" name='cfdi' placeholder="Uso cfdi" value="<?php echo $cfdi; ?>" maxlength='200'>
					</div>

					<div class="col-4">
						<label>RFC:</label>
							<input type="text" class="form-control form-control-sm" name="rfc" id="rfc" value="<?php echo $rfc;?>" placeholder="RFC" maxlength='13'>
					</div>

					<div class="col-8">
						<label>Nombre:</label>
							<input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre;?>" placeholder="Nombre" required maxlength='200'>
					</div>
				</div>
				<hr>

				<div class='row'>
					<div class="col-12">
						<label>Dirección</label>
						<input type="text" class="form-control form-control-sm" id="direccion" name='direccion' placeholder="Dirección" value="<?php echo $direccion;?>" maxlength='200'>
					</div>
					<div class="col-4">
						<label>Entre calles</label>
						<input type="text" class="form-control form-control-sm" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles;?>" maxlength='200'>
					</div>
					<div class="col-4">
						<label>No. exterior</label>
						<input type="text" class="form-control form-control-sm" id="numero" name='numero' placeholder="No. exterior" value="<?php echo $numero;?>" maxlength='20'>
					</div>
					<div class="col-4">
						<label>Colonia</label>
						<input type="text" class="form-control form-control-sm" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia;?>" maxlength='150'>
					</div>
					<div class="col-4">
						<label>Ciudad</label>
						<input type="text" class="form-control form-control-sm" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad;?>" maxlength='150' >
					</div>
					<div class="col-4">
						<label>Código postal</label>
						<input type="text" class="form-control form-control-sm" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp;?>" maxlength='5' >
					</div>
					<div class="col-4">
						<label>País</label>
						<input type="text" class="form-control form-control-sm" id="pais" name='pais' placeholder="País" value="<?php echo $pais;?>" maxlength='100'>
					</div>
					<div class="col-4">
						<label>Estado</label>
						<input type="text" class="form-control form-control-sm" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado;?>" maxlength='100'>
					</div>
					<div class="col-3">
						<label>Correo:</label>
							<input type="text" class="form-control form-control-sm" name="correo" id="correo" value="<?php echo $correo;?>" placeholder="Correo" maxlength='45'>
					</div>
					<div class="col-3">
						<label>Telefono:</label>
							<input type="text" class="form-control form-control-sm" name="telefono" id="telefono" value="<?php echo $telefono;?>" placeholder="Telefono" maxlength='45'>
					</div>

					<div class="col-12">
						<label>Observaciones:</label>
							<textarea type="text" class="form-control form-control-sm" name="observaciones" id="observaciones" placeholder="Observaciones" rows='5'><?php echo $observaciones;?></textarea>
					</div>
				</div>
			</div>
			<div class='card-footer'>
				<div class="row">
					<div class="col-sm-12">

						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link'  des='a_cliente/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
