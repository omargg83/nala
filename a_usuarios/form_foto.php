<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
?>
<form is="f-submit" id="form_foto" db="a_usuarios/db_" fun="foto" cmodal="1" des="a_usuarios/editar" desid='id' cmodal='1'>
<div class='modal-header'>
	<h5 class='modal-title'>Actualizar foto</h5>
</div>
  <div class='modal-body' >
		<?php
			echo "<input  type='hidden' id='idusuario' NAME='idusuario' value='$id'>";
		?>
		<input type="file" name="foto" id="foto" value=""	>
	</div>
	<div class="modal-footer">
		<button class='btn btn-warning btn-sm' type='submit'><i class='far fa-save'></i>Guardar</button>
		<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal" title='Cancelar'><i class='fas fa-undo-alt'></i>Cancelar</button>
  </div>
</form>
