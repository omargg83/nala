<?php
	require_once("db_.php");
	$idproducto=$_REQUEST['idproducto'];
	$cate=$db->categoria();
	if($idproducto>0){
		$per = $db->producto_editar($idproducto);

		$exist=$per->cantidad;
		$precio=$per->precio;
		$preciom=$per->preciom;
		$preciod=$per->preciod;
		$stockmin=$per->stockmin;
		$codigo=$per->codigo;

		$preciocompra=$per->preciocompra;
		$idcatalogo=$per->idcatalogo;
		$nombre=$per->nombre;
		$descripcion=$per->descripcion;
		$tipo=$per->tipo;
		$activo_producto=$per->activo_producto;


		$esquema=$per->esquema;
		$precio_mayoreo=$per->precio_mayoreo;
		$precio_distri=$per->precio_distri;
		//// variables esquema NALA
		$cantidad_mayoreo=$per->cantidad_mayoreo;
		$monto_mayor=$per->monto_mayor;
		$monto_distribuidor=$per->monto_distribuidor;
		///// varibables esquema 2
		$mayoreo_cantidad=$per->mayoreo_cantidad;
		$distri_cantidad=$per->distri_cantidad;
		///

	}
	else{
		$precio=0;

		$preciom=0;
		$preciod=0;
		$stockmin=1;

		$cantidad=0;
		$preciocompra="";

		$nombre="";
		$descripcion="";
		$tipo="";
		$activo_producto=0;

		$esquema=0;
		//// variables esquema NALA
		$cantidad_mayoreo=0;
		$precio_mayoreo=0;
		$monto_mayor=1000;
		$monto_distribuidor=3000;
		///// variables esquema 2
		$mayoreo_cantidad=0;
		$distri_cantidad=0;
		$precio_distri=0;


		$codigo=0;
	}

?>

<div class='container'>
	<form is="f-submit" id="form_editar" db="a_inventario/db_" fun="guardar_producto" des="a_inventario/editar" desid='idproducto'>
		<input type="hidden" name="idproducto" id="idproducto" value="<?php echo $idproducto;?>">
		<div class='card'>
			<div class='card-header'>
				<?php echo $nombre;?>
			</div>

			<form id='form_producto' action='' data-lugar='/db_' data-destino='/editar' data-funcion='guardar_producto'>
				<div class='card-body'>
					<input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $idproducto; ?>">
					<div class='row'>
						<div class="col-12">
						 <label>Tipo de producto</label>
							<select class="form-control form-control-sm" name="tipo" id="tipo" <?php if ($idproducto>0){ echo "readonly";}  ?> required>
								<option value='' disabled selected>Seleccione una opción</option>
								<option value="3"<?php if($tipo=="3") echo "selected"; ?> > Producto (Se controla el inventario por volúmen)</option>
								<option value="0"<?php if($tipo=="0") echo "selected"; ?> > Servicio (solo registra ventas, no es necesario registrar entrada)</option>
							</select>
						</div>
					</div>
					<hr>
					<div class='row'>
						<div class="col-2">
						 <label>Codigo</label>
						 <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Descripción" value="<?php echo $codigo; ?>" readonly>
						</div>
						<div class="col-8">
						 <label>Nombre</label>
						 <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Descripción" value="<?php echo $nombre; ?>" readonly>
						</div>

						<div class="col-2">
						 <label>Activo</label>
							<select class="form-control form-control-sm" name="activo_producto" id="activo"  >
								<option value="0"<?php if($activo_producto=="0") echo "selected"; ?> > Inactivo</option>
								<option value="1"<?php if($activo_producto=="1") echo "selected"; ?> > Activo</option>
							</select>
						</div>


						<div class="col-12">
						 <label>Descripción</label>
						 <textarea class="form-control form-control-sm" id="descripcion" name='descripcion' readonly rows='5'><?php echo $descripcion; ?></textarea>
						</div>
					</div>
				</div>
				<div class='card-footer'>
					<div class='row'>
						<div class="col-12">
							<?php
							if($idproducto>0){
								echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_productos/editar' dix='trabajo' v_idcatalogo='$idcatalogo'><i class='fas fa-pencil-alt'></i>Editar</button>";

							}
							?>
						</div>
					</div>
				</div>
				<div class='card-body'>
					<div class='row'>
						<div class="col-2">
						 <label>$ compra x Unidad</label>
						 <input type="text" class="form-control form-control-sm" id="preciocompra" name='preciocompra' placeholder="Precio" value="<?php echo $preciocompra; ?>">
						</div>

						<div class="col-2">
							<?php
							if($tipo==3){
								$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$idproducto'";
								$sth = $db->dbh->prepare($sql);
								$sth->execute();
								$cantidad=$sth->fetch(PDO::FETCH_OBJ);
								$exist=$cantidad->total;
							}
							?>
						 <label>Existencias</label>
						 <input type="text" class="form-control form-control-sm" id="tmp_ex" name='tmp_ex' placeholder="Existencias" value="<?php echo $exist; ?>" readonly>
						</div>

						<div class="col-2">
						 <label>Stock Minimo</label>
						 <input type="text" class="form-control form-control-sm" id="stockmin" name='stockmin' placeholder="Stock Minimo" value="<?php echo $stockmin; ?>">
						</div>

						<div class="col-2">
						 <label>$ Venta</label>
						 <input type="text" class="form-control form-control-sm" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>" required>
						</div>

						<div class="col-2">
						 <label>$ Mayoreo</label>
						 <input type="text" class="form-control form-control-sm" id="precio_mayoreo" name='precio_mayoreo' placeholder="Precio Mayoreo" value="<?php echo $precio_mayoreo; ?>" required>
						</div>

						<div class="col-2">
						 <label>$ Distribuidor</label>
						 <input type="text" class="form-control form-control-sm" id="precio_distri" name='precio_distri' placeholder="Precio Distribuidor" value="<?php echo $precio_distri; ?>" required>
						</div>

					</div>

					<hr>
					<h5>Esquema de descuento:</h5>
					<hr>
						<div class='row'>
								<div class='col-3'>
							<select class="form-control form-control-sm" name="esquema" id="esquema"required>
								<option value='' disabled selected>Seleccione una opción</option>
								<option value='0'<?php if($esquema=='0') echo 'selected'; ?> >NINGUNO</option>
								<option value='1'<?php if($esquema=='1') echo 'selected'; ?> >NALA</option>
								<option value='2'<?php if($esquema=='2') echo 'selected'; ?> >ESQUEMA 2</option>
							</select>
								</div>

					</div>

					<hr>
					<h5>Esquema NALA:</h5>
					<hr>
					<div class='row'>
						<div class="col-2">
						 <label>Cantidad min. Mayoreo (Pza.)</label>
						 <input type="text" class="form-control form-control-sm" id="cantidad_mayoreo" name='cantidad_mayoreo' placeholder="# Cant. Mayoreo" value="<?php echo $cantidad_mayoreo; ?>" >
						</div>

						<div class="col-2">
						 <label>Monto min. compra mayoreo</label>
						 <input type="text" class="form-control form-control-sm" id="monto_mayor" name='monto_mayor' placeholder="Monto min compra mayoreo" value="<?php echo $monto_mayor; ?>" readonly>
						</div>

						<div class="col-2">
						 <label>Monto min. compra distribuidor</label>
						 <input type="text" class="form-control form-control-sm" id="monto_distribuidor" name='monto_distribuidor' placeholder="Monto min compra distribuidor" value="<?php echo $monto_distribuidor; ?>" readonly>
						</div>

					</div>
					<hr>
					<h5>Esquema 2:</h5>
					<hr>
					<div class='row'>
						<div class="col-2">
						 <label>Cantidad Para Precio Mayoreo (Pza.)</label>
						 <input type="text" class="form-control form-control-sm" id="mayoreo_cantidad" name='mayoreo_cantidad' placeholder="# Cant. Mayoreo" value="<?php echo $mayoreo_cantidad; ?>" >
						</div>

						<div class="col-2">
						 <label>Cantidad Para Precio Distribuidor (Pza.)</label>
						 <input type="text" class="form-control form-control-sm" id="distri_cantidad" name='distri_cantidad' placeholder="# Cant. Mayoreo" value="<?php echo $distri_cantidad; ?>" >
						</div>

					</div>
				</div>

				<div class='card-footer'>
					<div class='row'>
						<div class="col-12">
								<?php
									echo "<button type='submit' class='btn btn-warning btn-sm'><i class='far fa-save'></i>Guardar</button>";

									if($idproducto>0){

										echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-print' title='Editar' des='a_inventario/imprimir' dix='trabajo' v_idcatalogo='$idcatalogo' v_variable='demo' v_tipo='1'><i class='fas fa-barcode'></i>Barras</button>";


										if($tipo==3){
											echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_inventario/form_agrega' omodal='1' v_id='0' v_idproducto='$idproducto' ><i class='fas fa-key'></i>+ existencias</button>";
										}
									}
								?>
								<button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_inventario/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
						</div>
					</div>
				</div>
			</form>


			<?php
				$row=$db->productos_inventario($idproducto);
				echo "<table class='table table-sm' style='font-size:12px'>";
				echo "<tr>
				<th>-</th>
				<th>Fecha</th>
				<th>Cantidad</th>
				<th>Precio</th>
				</tr>";
				$total=0;
				foreach($row as $key){
					echo "<tr id='".$key->idbodega."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
							if(!$key->idventa){

							echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_inventario/editar' desid='idbodega' dix='trabajo' db='a_inventario/db_'  fun='borrar_ingreso' v_idbodega='$key->idbodega' v_idproducto='$idproducto' tp='¿Desea eliminar la entrada?' title='Borrar'><i class='far fa-trash-alt'></i></button>";
							}
							echo "</div>";
						echo "</td>";
						echo "<td>";
							echo fecha($key->fecha);
						echo "</td>";

						echo "<td>";
							echo $key->cantidad;
						echo "</td>";
						echo "<td>";
							echo moneda($key->c_precio);
						echo "</td>";

					echo "</tr>";
				}
				echo "</table>";
			?>

			</div>
		</div>
		</form>
</div>
