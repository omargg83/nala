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
							 echo "Producto (Se controla el inventario por volúmen)";
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
						<div class="col-4">
						 <label><b>Existencias</b></label>
						 <input type="text" class="form-control form-control-sm" id="tmp_ex" name='tmp_ex' placeholder="Existencias" value="<?php echo $exist; ?>" readonly>
						</div>


						<div class="col-4">
						 <label>Precio compra</label>
						 <input type="text" class="form-control form-control-sm" id="preciocompra" name='preciocompra' placeholder="Precio" value="<?php echo $preciocompra; ?>">
						</div>

						<div class="col-4">
						 <label>Stock Minimo</label>
						 <input type="text" class="form-control form-control-sm" id="stockmin" name='stockmin' placeholder="Stock Minimo" value="<?php echo $stockmin; ?>">
						</div>

						<div class="col-4">
						 <label>$ Venta</label>
						 <input type="text" class="form-control form-control-sm" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>" required>
						</div>

						<div class="col-4">
						 <label>$ Mayoreo</label>
						 <input type="text" class="form-control form-control-sm" id="precio_mayoreo" name='precio_mayoreo' placeholder="Precio Mayoreo" value="<?php echo $precio_mayoreo; ?>">
						</div>

						<div class="col-4">
						 <label>$ Distribuidor</label>
						 <input type="text" class="form-control form-control-sm" id="precio_distri" name='precio_distri' placeholder="Precio Distribuidor" value="<?php echo $precio_distri; ?>">
						</div>

					</div>
					<hr>
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
					<p><b>Esquema NALA:</b></p>
					<div class='row mb-3' >
						<div class="col-4">
						 <label>Cantidad min. Mayoreo (Pza.)</label>
						 <input type="text" class="form-control form-control-sm" id="cantidad_mayoreo" name='cantidad_mayoreo' placeholder="# Cant. Mayoreo" value="<?php echo $cantidad_mayoreo; ?>" >
						</div>

						<div class="col-4">
						 <label>Monto min. compra mayoreo</label>
						 <input type="text" class="form-control form-control-sm" id="monto_mayor" name='monto_mayor' placeholder="Monto min compra mayoreo" value="<?php echo $monto_mayor; ?>" >
						</div>

						<div class="col-4">
						 <label>Monto min. compra distribuidor</label>
						 <input type="text" class="form-control form-control-sm" id="monto_distribuidor" name='monto_distribuidor' placeholder="Monto min compra distribuidor" value="<?php echo $monto_distribuidor; ?>" >
						</div>
					</div>
					<hr>
					<p><b>Esquema 2:</b></p>
					<div class='row mb-3'>
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
						<div class='btn-group'>
							<?php
								if($_SESSION['a_sistema']==1){
									if($db->nivel_captura==1){
										if($_SESSION['matriz']==1){
											echo "<button type='submit' class='btn btn-warning btn-sm'><i class='far fa-save'></i>Guardar</button>";
										}
									}
								}

								if($idproducto>0){
									if($_SESSION['matriz']==1){
										echo "<button type='button' class='btn btn-warning btn-sm' id='genera_Barras' is='b-link' title='Editar' tp='¿Desea generar el codigo de barras?' db='a_inventario/db_' fun='barras' des='a_inventario/editar' dix='trabajo' v_idproducto='$idproducto' v_idcatalogo='$idcatalogo'><i class='fas fa-barcode'></i>Barras</button>";
									}

									echo "<button type='button' class='btn btn-warning btn-sm' id='Imprime_barras' is='b-print' title='Editar' des='a_inventario/imprimir' v_idcatalogo='$idcatalogo'><i class='fas fa-print'></i>Barras</button>";
								}


								if($idproducto>0){
									if($tipo==3){
										echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_inventario/form_agrega' omodal='1' v_id='0' v_idproducto='$idproducto' ><i class='fas fa-key'></i>+ existencias</button>";
									}
								}

								echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='control_db' des='a_inventario/editar' fun='recalcular' dix='trabajo' v_idproducto='$idproducto' v_ctrl='control' id='recal' tp='¿Desea recalcular?'><i class='fas fa-exclamation-triangle'></i>+ Recalcular</button>";

							?>
							<button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_inventario/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<br>

<div class='container' id='registro_bodega'>
	<?php
		include "lista_bodega.php";
 	?>
</div>
