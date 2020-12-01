<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$idproducto=$_REQUEST['idproducto'];

	$cantidad=1;
	$fecha=date("Y-m-d");
	$nota="";
	$precio=0;
?>

<form is="f-submit" id="form_inventario" db="a_inventario/db_" fun="existencia_agrega" des="a_inventario/editar" desid='idproducto' cmodal='2'>
<div class='modal-header'>
	<h5 class='modal-title'>Agregar existencia</h5>
</div>
  <div class='modal-body' >
	<?php
		echo "<input type='hidden' id='id' NAME='id' value='$id'>";
		echo "<input type='hidden' id='idproducto' NAME='idproducto' value='$idproducto'>";
	?>
		<div class='row'>
			<div class="col-xl col-auto">
			 <label>Cantidad</label>
			 <input type="text" class="form-control form-control-sm" id="cantidad" name='cantidad' placeholder="Cantidad" value="<?php echo $cantidad; ?>">
			</div>
			<div class="col-xl col-auto">
			 <label>Precio de compra</label>
			 <input type="text" class="form-control form-control-sm" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>">
			</div>
			<div class="col-xl col-auto">
			 <label>Nota de compra</label>
			 <?php
			 	echo "<select type='text' class='form-control form-control-sm' id='idcompra' name='idcompra'>";
					echo "<option value='0'></option>";
					foreach ($db->compras_lista() as $v2){
						echo "<option value='$v2->idcompra'>$v2->nombre</option>";
					}
				echo "</select>";
			?>
			</div>
		</div>
	</div>

		<div class='modal-footer' >
			<div class='btn-group'>
	 			<?php
					if($db->nivel_captura==1){
						echo "<button class='btn btn-warning btn-sm' type='submit' is='f-submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>";
					}
				?>
				<button class="btn btn-warning btn-sm" type="button" is="b-link" cmodal='1' ><i class="fas fa-sign-out-alt"></i>Cerrar</button>
			</div>
	  </div>
</form>
