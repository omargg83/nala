<?php
	require_once("db_.php");
	if (isset($_REQUEST['idcatalogo'])){$idcatalogo=$_REQUEST['idcatalogo'];} else{ $idcatalogo=0;}

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
?>

		<div class='card'>
			<div class='card-header'>
				<?php echo $nombre;?>
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i>Cerrar</button>
			</div>
			<div class='card-body'>
				<div class='tab-content' id='myTabContent'>
					<div class='tab-pane fade show active' id='ssh' role='tabpanel' aria-labelledby='ssh-tab'>
							<div class='row'>
								<div class="col-xl col-auto">
									<label>ID</label><br>
									<p><?php echo $idcatalogo; ?></p>
								</div>
								<div class="col-xl col-auto">
								 <label>Tipo de producto</label>
								 	<?php
											if($tipo==0){
												echo "<input type='text' class='form-control form-control-sm' value='Servicio (solo registra ventas, no es necesario registrar entrada)' readonly>";
											}
											if($tipo==3){
												echo "<input type='text' class='form-control form-control-sm' value='Producto (Se controla el inventario por volúmen)' readonly>";
											}
									?>
								</div>
							</div>
							<div class='row'>
								<div class="col-xl col-auto">
									<label>Código</label><br>
									<p><?php echo $codigo; ?>"</p>
								</div>

								<div class="col-xl col-auto">
								 <label><b>Nombre</b></label><br>
								 <p><?php echo $nombre; ?>"</p>
								</div>

								<div class='col-xl col-auto'>
									<label>categoría</label>
									<select class='form-control form-control-sm' name='categoria' id='categoria'>
										<?php
										foreach($cate as $key){
											echo  "<option value='".$key->idcat."' "; if($categoria==$key->idcat){ echo " selected";} echo ">".$key->nombre."</option>";
										}?>
									</select>
								</div>

								<div class="col-xl col-auto">
									<label>Descripción</label>
									 <p><?php echo $descripcion; ?>"</p>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			echo "<div style='max-height:200px;overflow:auto;'>";
			echo "<table class='table'>";
			foreach($db->productos_homologar($idcatalogo) as $prod){
				echo "<tr>";

				echo "<td>";
				echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_productos/db_' des='a_productos/lista' fun='homologa_final' dix='trabajo' v_origen='$idcatalogo' v_destino='$prod->idcatalogo' id='eliminar".$prod->idcatalogo."' tp='¿Desea eliminar el producto seleccionado?'><i class='far fa-trash-alt'></i></button>";
				echo "</td>";

				echo "<td>";
				echo $prod->idcatalogo;
				echo "</td>";
				echo "<td>";
				echo $prod->codigo;
				echo "</td>";
				echo "<td>";
				echo $prod->nombre;
				echo "</td>";
				echo "<td>";
				echo $prod->descripcion;
				echo "</td>";
				echo "<tr>";
			}
			echo "</table>";
			echo "</div>";
		?>
