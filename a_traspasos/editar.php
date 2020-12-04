<?php
	require_once("db_.php");
	if (isset($_REQUEST['idtraspaso'])){$idtraspaso=$_REQUEST['idtraspaso'];} else{ $idtraspaso=0;}
	$sucursal=$db->sucursal_lista();

	if($idtraspaso>0){
		$traspaso = $db->traspaso($idtraspaso);
		$numero=$traspaso->numero;
		$nombre=$traspaso->nombre;
		$idsucursal=$traspaso->idsucursal;
		$estado=$traspaso->estado;
		$fecha=$traspaso->fecha;
	}
	else{
		$numero="0";
		$nivel="1";
		$nombre="";
		$estado="Activa";
		$fecha=date("Y-m-d");
		$idsucursal=$_SESSION['idsucursal'];
	}
?>
<div class="container">
	<div class='card'>
		<div class='card-header'>
			Traspasos
			<?php echo "<br>(".$estado.")"; ?>
		</div>
		<form is="f-submit" id="form_personal" db="a_traspasos/db_" fun="guardar_traspaso" des='a_traspasos/editar' desid='idtraspaso'>
			<div class='card-body'>
				<input type="hidden" class="form-control form-control-sm" name="idtraspaso" id="idtraspaso" value="<?php echo $idtraspaso ;?>" placeholder="Tienda" readonly>
				<div class='row'>

				 <div class='col-12 col-xl col-auto'>
				   <label>Número:</label>
					 <input type="text" class="form-control form-control-sm" name="numero" id="numero" value="<?php echo $numero ;?>" placeholder="Número" required readonly>
				 </div>

				 <div class='col-12 col-xl col-auto'>
				   <label>Identificador:</label>
					 <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre ;?>" placeholder="identificador" required>
				 </div>

				 <div class='col-12 col-xl col-auto'>
				   <label>Fecha:</label>
					 <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" value="<?php echo $fecha ;?>" placeholder="Fecha" required>
				 </div>

				<div class='col-12 col-xl col-auto'>
				  <label >Enviar a:</label>
					<?php
						echo "<select class='form-control form-control-sm' name='idsucursal' id='idsucursal'>";
						foreach($sucursal as $v1){
							  echo '<option value="'.$v1->idsucursal.'"';
							  if($v1->idsucursal==$idsucursal){
								  echo " selected";
							  }
							  echo '>'.$v1->nombre.'</option>';
						}
					  echo "</select>";
					?>
				</div>

				<div class='col-12 col-xl col-auto'>
					<label>Estado:</label>
					<input type="text" class="form-control form-control-sm" value="<?php echo $estado ;?>" placeholder="Fecha" readonly>
				</div>
			</div>
		</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-12 col-xl col-auto">
						<div class="btn-group">
							<?php
								if($estado=="Activa"){
									echo "<button class='btn btn-warning btn-sm' type='submit'><i class='far fa-save'></i>Guardar</button>";
								}
								if($idtraspaso>0){
									if($estado=="Activa"){
										echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_traspasos/db_' des='a_traspasos/editar' desid='$idtraspaso' fun='enviar_traspaso' dix='trabajo' v_idtraspaso='$idtraspaso' id='finaliza' tp='¿Desea procesar el traspaso seleccionado?'><i class='fas fa-cloud-upload-alt'></i>Enviar</button>";

										echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_traspasos/form_producto' omodal='1'><i class='fas fa-plus'></i>Producto</button>";
									}
									else{
										if($idsucursal!=$_SESSION['idsucursal']){
											echo "<button class='btn btn-warning btn-sm' type='button' is='b-print' des='a_traspasos/imprimir' v_idtraspaso='$idtraspaso' omodal='1'><i class='fas fa-print'></i>Imprimir</button>";

											if($db->nivel_personal==0 and $estado!="Recibida"){
												echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_traspasos/db_' des='a_traspasos/editar' desid='$idtraspaso' fun='abrir_traspaso' dix='trabajo' v_idtraspaso='$idtraspaso' id='finaliza' tp='¿Desea abrir el traspaso seleccionado?'><i class='fas fa-lock-open'></i>Desbloquear</button>";
											}
										}
									}
								}
							?>
							<button type="button" class='btn btn-warning btn-sm' id='lista_penarea' is="b-link" des='a_traspasos/lista' dix='trabajo'><i class='fas fa-undo-alt'></i>Regresar</button>
							</div>
					</div>
				</div>
			</div>
		</form>
<?php
	if($idtraspaso>0){
		echo "<div class='col-12 col-xl col-auto' id='lista'>";
			include 'lista_pedido.php';
		echo "</div>";
	}
?>
	</div>
</div>
