<?php
	require_once("db_.php");
	if (isset($_REQUEST['idcatalogo'])){$idcatalogo=$_REQUEST['idcatalogo'];} else{ $idcatalogo=0;}
	$codigo="";
	$nombre="";
	$unidad="";

	$categoria=0;

	$marca="";
	$modelo="";
	$descripcion="";
	$tipo="";
	$activo_catalogo="1";

	$color="";

	$cate=$db->categoria();
	if($idcatalogo>0){
		$pd = $db->producto_edit($idcatalogo);
		$codigo=$pd->codigo;
		$nombre=$pd->nombre;
		$unidad=$pd->unidad;
		$marca=$pd->marca;
		$modelo=$pd->modelo;
		$descripcion=$pd->descripcion;
		$tipo=$pd->tipo;
		$activo_catalogo=$pd->activo_catalogo;
		$categoria=$pd->categoria;
		$color=$pd->color;
	}

?>

<div class='container'>
	<form is="f-submit" id="form_editar" db="a_productos/db_" fun="guardar_producto" des="a_productos/editar" desid='idcatalogo'>
		<input type="hidden" name="idcatalogo" id="idcatalogo" value="<?php echo $idcatalogo;?>">
		<div class='card'>
			<div class='card-header'>
				<?php echo $nombre;?>
			</div>
			<div class='card-body'>
				<div class='tab-content' id='myTabContent'>
					<div class='tab-pane fade show active' id='ssh' role='tabpanel' aria-labelledby='ssh-tab'>
							<div class='row'>
								<div class="col-12">
								 <label>Tipo de producto</label>
									<select class="form-control form-control-sm" name="tipo" id="tipo" <?php if ($idcatalogo>0){ echo "readonly";}  ?> required>
										<option value='' disabled selected>Seleccione una opción</option>
										<option value="3"<?php if($tipo=="3") echo "selected"; ?> > Producto (Se controla el inventario por volúmen)</option>
										<option value="0"<?php if($tipo=="0") echo "selected"; ?> > Servicio (solo registra ventas, no es necesario registrar entrada)</option>
									</select>
								</div>
							</div>
							<hr>
							<div class='row'>
								<div class="col-5">
								 <label>Nombre</label><br>

								 <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
								</div>

								<div class='col-3'>
										<label>categoría</label>
										<select class='form-control form-control-sm' name='categoria' id='categoria'>
											<?php
											foreach($cate as $key){
												echo  "<option value='".$key->idcat."' "; if($categoria==$key->idcat){ echo " selected";} echo ">".$key->nombre."</option>";
											}?>
										</select>
									</div>

								<div class="col-12">
								 <label>Descripción</label>
								 <textarea class="form-control form-control-sm" id="descripcion" name='descripcion' placeholder="Descripción" rows='5'><?php echo $descripcion; ?></textarea>
								</div>
							</div>
							<div class='row'>

							</div>

							<div class='row'>

								<div class="col-3">
								 <label>Activo</label>
									<select class="form-control form-control-sm" name="activo_catalogo" id="activo_catalogo"  >
										<option value="0"<?php if($activo_catalogo=="0") echo "selected"; ?> > Inactivo</option>
										<option value="1"<?php if($activo_catalogo=="1") echo "selected"; ?> > Activo</option>
									</select>
								</div>

							</div>
							<hr>

							<div class='row'>
								<div class="col-12">
										<?php
											echo "<button type='submit' class='btn btn-warning btn-sm'><i class='far fa-save'></i>Guardar</button>";
										?>
										<button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_inventario/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
								</div>
							</div>
					</div>

				</div>
			</div>
		</div>
		</form>
</div>
