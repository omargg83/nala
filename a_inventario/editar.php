<?php
	require_once("db_.php");
	$idproducto=$_REQUEST['idproducto'];
	$cate=$db->categoria();
	if($idproducto>0){
		$per = $db->producto_editar($idproducto);

		$exist=$per->cantidad;
		$precio=$per->precio;
		$stockmin=$per->stockmin;
		$codigo=$per->codigo;
		$archivo=$per->archivo;

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
		<div class='card'>
			<form is="f-submit" id="form_editar" db="a_inventario/db_" fun="guardar_producto" des="a_inventario/editar" desid='idproducto'>
				<input type="hidden" name="idproducto" id="idproducto" value="<?php echo $idproducto;?>">
				<div class='card-header'>
					<div class='row'>
						<div class='col-12'>
							<?php echo "<b>".$nombre."</b>";

							 echo "<br><small>";
							 if($tipo==0){
								 echo "Servicio (solo registra ventas, no es necesario registrar entrada)";
							 }
							 if($tipo==3){
								 echo "Producto (Se controla el inventario por volúmen";
							 }
							 echo "</small>";
							?>
						</div>
					</div>
				</div>


				<div class='card-body'>
					<div class='row'>
						<div class="col-2">
							<?php
								echo "<img src='".$db->f_productos."/".$archivo."' width='100%' class='img-thumbnail'/>";
						 	?>
						</div>
						<div class="col-10">
							<div class='row'>
								<div class="col-2">
								 <label><b>Codigo</b></label>
								 <p><?php echo $codigo; ?></p>

								</div>
								<div class="col-8">
								 <label><b>Producto</b></label>
								 <p><?php echo $nombre; ?></p>
								</div>

								<div class="col-2">
								 <label><b>Activo</b></label>
								 <p><?php
								 		if($activo_producto=="1"){ echo "Activo"; }
								 		if($activo_producto=="0"){ echo "Inactivo"; }
								  ?></p>
								</div>

								<div class="col-12" style='max-height:100px;'>
								 <label><b>Descripción</b></label>
								 <p><?php echo $descripcion; ?></p>
								</div>
							</div>
						</div>
					</div>



					<hr>
				<?php
				if($tipo==3){
					$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$idproducto'";
					$sth = $db->dbh->prepare($sql);
					$sth->execute();
					$cantidad=$sth->fetch(PDO::FETCH_OBJ);
					$exist=$cantidad->total;
				}
				?>

						<div class='row mb-3'>
							<div class="col-2">
							 <label><b>Existencias</b></label>
							 <input type="text" class="form-control form-control-sm" id="tmp_ex" name='tmp_ex' placeholder="Existencias" value="<?php echo $exist; ?>" readonly>
							</div>


							<div class="col-2">
							 <label>Precio compra</label>
							 <input type="text" class="form-control form-control-sm" id="preciocompra" name='preciocompra' placeholder="Precio" value="<?php echo $preciocompra; ?>">
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
							 <input type="text" class="form-control form-control-sm" id="precio_mayoreo" name='precio_mayoreo' placeholder="Precio Mayoreo" value="<?php echo $precio_mayoreo; ?>">
							</div>

							<div class="col-2">
							 <label>$ Distribuidor</label>
							 <input type="text" class="form-control form-control-sm" id="precio_distri" name='precio_distri' placeholder="Precio Distribuidor" value="<?php echo $precio_distri; ?>">
							</div>

						</div>

						<div class='row mb-3'>
							<div class='col-3'>
								<p><b>Esquema de descuento:</b></p>
							</div>
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
						<div class='row mb-3' >
							<div class="col-3">
								<br><p><b>Esquema NALA:</b></p>
							</div>
							<div class="col-3">
							 <label>Cantidad min. Mayoreo (Pza.)</label>
							 <input type="text" class="form-control form-control-sm" id="cantidad_mayoreo" name='cantidad_mayoreo' placeholder="# Cant. Mayoreo" value="<?php echo $cantidad_mayoreo; ?>" >
							</div>

							<div class="col-3">
							 <label>Monto min. compra mayoreo</label>
							 <input type="text" class="form-control form-control-sm" id="monto_mayor" name='monto_mayor' placeholder="Monto min compra mayoreo" value="<?php echo $monto_mayor; ?>" >
							</div>

							<div class="col-3">
							 <label>Monto min. compra distribuidor</label>
							 <input type="text" class="form-control form-control-sm" id="monto_distribuidor" name='monto_distribuidor' placeholder="Monto min compra distribuidor" value="<?php echo $monto_distribuidor; ?>" >
							</div>
						</div>
						<hr>
						<div class='row mb-3'>
							<div class="col-4">
								<br><p><b>Esquema 2:</b></p>
							</div>
							<div class="col-4">
							 <label>Cantidad Para Precio Mayoreo (Pza.)</label>
							 <input type="text" class="form-control form-control-sm" id="mayoreo_cantidad" name='mayoreo_cantidad' placeholder="# Cant. Mayoreo" value="<?php echo $mayoreo_cantidad; ?>" >
							</div>

							<div class="col-4">
							 <label>Cantidad Para Precio Distribuidor (Pza.)</label>
							 <input type="text" class="form-control form-control-sm" id="distri_cantidad" name='distri_cantidad' placeholder="# Cant. Mayoreo" value="<?php echo $distri_cantidad; ?>" >
							</div>

						</div>
					</div>

				<div class='card-footer'>
					<div class='row'>
						<div class="col-12">
								<?php
									echo "<div class='btn-group'>";
									echo "<button type='submit' class='btn btn-warning btn-sm'><i class='far fa-save'></i>Guardar</button>";


										if($idproducto>0){
											echo "<button type='button' class='btn btn-warning btn-sm' id='genera_Barras' is='b-link' title='Editar' tp='¿Desea generar el codigo de barras?' db='a_inventario/db_' fun='barras' des='a_inventario/editar' dix='trabajo' v_idproducto='$idproducto' v_idcatalogo='$idcatalogo'><i class='fas fa-barcode'></i>Barras</button>";

											echo "<button type='button' class='btn btn-warning btn-sm' id='Imprime_barras' is='b-print' title='Editar' des='a_inventario/imprimir' v_idcatalogo='$idcatalogo'><i class='fas fa-print'></i>Barras</button>";
										}


									if($idproducto>0){
										if($tipo==3){
											echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_inventario/form_agrega' omodal='1' v_id='0' v_idproducto='$idproducto' ><i class='fas fa-key'></i>+ existencias</button>";
										}
									}
								?>
								<button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_inventario/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>

							</div>
						</div>
					</div>
				</div>
			</form>

			<div class='card-body'>
			<?php
				$row=$db->productos_inventario($idproducto);
				echo "<table class='table table-sm' style='font-size:12px'>";
				echo "<tr>
				<th>-</th>
				<th>Fecha</th>
				<th>Tipo</th>
				<th>Descripción</th>
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
							if($key->cantidad>0 and strlen($key->idcompra)==0){
								echo "Ingreso";
							}
							if($key->cantidad>0 and strlen($key->idcompra)>0){
								echo "Compra";
							}
							if($key->cantidad<0 and strlen($key->idventa)==0){
								echo "Venta";
							}
							if($key->cantidad<0 and strlen($key->idtraspaso)==0){
								echo "Traspaso";
							}
						echo "</td>";
						echo "<td>";
							if(strlen($key->idcompra)>0){
								$sql="select * from compras where idcompra=$key->idcompra";
								$sth = $db->dbh->query($sql);
								$res=$sth->fetch(PDO::FETCH_OBJ);
								echo "#:".$res->numero;
								echo "<br>".fecha($res->fecha);
								echo "<br>".$res->nombre;
							}
							if(strlen($key->idventa)>0){
								$sql="select * from venta where idventa=$key->idventa";
								$sth = $db->dbh->query($sql);
								$res=$sth->fetch(PDO::FETCH_OBJ);
								echo "#:".$res->numero;
								echo "<br>".fecha($res->fecha);
							}
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
</div>
