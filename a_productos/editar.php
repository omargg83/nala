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
	$archivo="";
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
		$archivo=$pd->archivo;
	}
?>

<div class='container'>
	<?php
		echo "<form is='f-submit' id='form_editar' db='a_productos/db_' fun='guardar_producto' des='a_productos/editar' desid='idcatalogo' dix='trabajo'>";
	?>
		<input type="hidden" name="idcatalogo" id="idcatalogo" value="<?php echo $idcatalogo;?>">
		<div class='card'>
			<div class='card-header'>
				<?php echo $nombre;?>
			</div>
			<div class='card-body'>
				<div class='row'>
					<div class="col-2">
						<?php
							if(strlen($archivo)>0 and file_exists("../".$db->f_productos."/".$archivo)){
								echo "<img src='".$db->f_productos."/".$archivo."' width='100%' class='img-thumbnail'/>";
							}
							else{
								echo "<img src='img/unnamed.png' width='100%' class='img-thumbnail'/>";
							}
					 ?>
					</div>
					<div class="col-10">
						<div class='row'>
								<div class="col-9">
									<label>Tipo de producto</label>
									<?php
									if($idcatalogo==0){
										echo "<select class='form-control form-control-sm' name='tipo' id='tipo' required>";
										echo "<option value='3'> Producto (Se controla el inventario por volúmen)</option>";
										echo "<option value='0'> Servicio (solo registra ventas, no es necesario registrar entrada)</option>";
										echo "</select>";
									}
									else{
										if($tipo==0){
											echo "<input type='text' class='form-control form-control-sm' value='Servicio (solo registra ventas, no es necesario registrar entrada)' readonly>";
										}
										if($tipo==3){
											echo "<input type='text' class='form-control form-control-sm' value='Producto (Se controla el inventario por volúmen)' readonly>";
										}
									}
									?>
							</div>
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
							<div class="col-2">
								<label>Código</label><br>
								<input type="text" class="form-control form-control-sm" id="codigo" name='codigo' placeholder="Codigo" value="<?php echo $codigo; ?>">
							</div>

							<div class="col-8">
								<label>Nombre</label><br>
								<input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
							</div>

							<div class='col-2'>
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

						<hr>
						<div class='row'>
							<div class="col-12">
								<div class="btn-group">
									<?php
									if($_SESSION['a_sistema']==1){
										if($db->nivel_captura==1){
											if($_SESSION['matriz']==1){
												echo "<button type='submit' class='btn btn-warning btn-sm'><i class='far fa-save'></i>Guardar</button>";
													if($idcatalogo>0){
														echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_productos/form_foto' v_idcatalogo='$idcatalogo' omodal='1'><i class='fas fa-camera'></i>Foto</button>";
														echo "<button type='button' class='btn btn-danger btn-sm' is='b-link' db='a_productos/db_' des='a_productos/lista' fun='borrar_producto' dix='trabajo' v_idcatalogo='$idcatalogo' id='eliminar' tp='¿Desea eliminar el Producto seleccionado?'><i class='far fa-trash-alt'></i>Eliminar</button>";
													}
											}
										}
									}
									echo "<button type='button' class='btn btn-warning btn-sm' id='regresar_lista' is='b-link' title='Editar' des='a_productos/lista' dix='trabajo'><i class='fas fa-undo-alt'></i>Regresar</button>";
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
</div>
