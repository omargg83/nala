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
		$estado="1";
		$fecha=date("Y-m-d");
		$idsucursal=$_SESSION['idsucursal'];
	}
?>
<div class="container">
	<div class='card'>
		<div class='card-header'>
			Traspasos
		</div>
		<form is="f-submit" id="form_personal" db="a_traspasos/db_" fun="guardar_traspaso" des='a_traspasos/editar' desid='idtraspaso'>
			<div class='card-body'>
				<input type="hidden" class="form-control form-control-sm" name="idtraspaso" id="idtraspaso" value="<?php echo $idtraspaso ;?>" placeholder="Tienda" readonly>
				<div class='row'>

				 <div class='col-3'>
				   <label>Número:</label>
					 <input type="text" class="form-control form-control-sm" name="numero" id="numero" value="<?php echo $numero ;?>" placeholder="Número" required readonly>
				 </div>

				 <div class='col-3'>
				   <label>Identificador:</label>
					 <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre ;?>" placeholder="identificador" required>
				 </div>

				 <div class='col-3'>
				   <label>Fecha:</label>
					 <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" value="<?php echo $fecha ;?>" placeholder="Fecha" required>
				 </div>


				<div class="col-3">
				 <label for="">Estado:</label>
					<select class="form-control form-control-sm" name="estado" id="estado">
					  <option value="Activa"<?php if($estado=="Activa") echo "selected"; ?> >Activa</option>
					  <option value="Cancelada"<?php if($estado=="Cancelada") echo "selected"; ?> >Inactivo</option>
					</select>
				</div>

				<div class="col-3">
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
			</div>
		</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-sm-12">
						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<button type="button" class='btn btn-warning btn-sm' id='lista_penarea' is="b-link" des='a_traspasos/lista' dix='trabajo'><i class='fas fa-undo-alt'></i>Regresar</button>
					</div>
				</div>
			</div>
		</form>

<?php
	if($idtraspaso>0){
		echo "<div class='col-12' id='lista' style='max-height:600px; overflow:auto;'>";
			include 'lista_pedido.php';
		echo "</div>";


		echo "<div class='card-body' >";
			echo "<form is='t-busca' id='form_busca' >";
				echo "<div clas='row'>";
						echo "<div class='input-group mb-3'>";
						echo "<input type='text' class='form-control form-control-sm' name='prod_venta' id='prod_venta' placeholder='buscar producto' aria-label='buscar producto' aria-describedby='basic-addon2'>";
						echo "<div class='input-group-append'>";
							echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fas fa-search'></i>Buscar</button>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</form>";
		echo "</div>";

		echo "<div clas='row' id='resultadosx' style='min-height:500px; max-height: 500; overflow:auto;'>
		</div>";
	}
?>
	</div>
</div>
