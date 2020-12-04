<?php
	require_once("db_.php");
	$db = new Gastos();
	$idgastos=$_REQUEST['idgastos'];
	if($idgastos>0){
		$pers = $db->gastos_edit($idgastos);
		$fecha=fecha($pers['fecha']);
		$gasto=$pers['gasto'];
		$descripcion=$pers['descripcion'];
		$costo=$pers['costo'];


	}
	else{
		$fecha=date("d-m-Y");
		$gasto="";
		$descripcion="";
		$costo="0";
	}
?>
<div class="container">
	<form is="f-submit" id="form_editar" db="a_gastos/db_" fun="guardar_gastos" lug="a_gastos/editar" desid='idgastos'>
			<input type="hidden" name="idgastos" id="idgastos" value="<?php echo $idgastos;?>">
		<div class="card">
			<div class="card-header">Gasto</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-xl col-auto">
						<label for="fecha">Fecha</label>
						<input type="text" placeholder="Fecha" id="fecha" name="fecha" value="<?php echo $fecha; ?>" class="form-control fechaclass" autocomplete=off >
					</div>

					<div class="col-12 col-xl col-auto">
						<label for="gasto">Gasto</label>
						<input type="gasto" placeholder="Gasto" id="gasto" name="gasto" value="<?php echo $gasto; ?>" class="form-control" required>
					</div>
					<div class="col-12 col-xl col-auto">
						<label for="descripcion">Descripción</label>
						<input type="text" placeholder="Descripción" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>" class="form-control" >
					</div>
					<div class="col-12 col-xl col-auto">
						<label for="costo">Costo</label>
						<input type="number" step='any' placeholder="Costo" id="costo" name="costo" value="<?php echo $costo; ?>" class="form-control" autocomplete=off  required dir='rtl'>
					</div>

				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-12 col-xl col-auto">
						<div class="btn-group">
							<?php
							if($db->nivel_captura==1){
								echo "<button class='btn btn-warning btn-sm' type='submit'><i class='far fa-save'></i>Guardar</button>";
								}
							?>
							<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link'  des='a_gastos/lista' dix='trabajo' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
