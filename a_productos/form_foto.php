<?php
	require_once("db_.php");
	$idcatalogo=$_REQUEST['idcatalogo'];
?>
<form is="f-submit" id="form_foto" db="a_productos/db_" fun="foto" cmodal="1" des="a_productos/editar" desid='idcatalogo' cmodal='1' dix='contenido'>
<div class='modal-header'>
	<h5 class='modal-title'>Actualizar foto</h5>
</div>
  <div class='modal-body' >
		<?php
			echo "<input  type='hidden' id='idcatalogo' NAME='idcatalogo' value='$idcatalogo'>";
		?>
		

		<input type="file" name="foto" id="foto" value=""	>

	</div>
	<div class="modal-footer">
		<button class='btn btn-warning btn-sm' type='submit'><i class='far fa-save'></i>Guardar</button>
		<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal" title='Cancelar'><i class='fas fa-undo-alt'></i>Cancelar</button>
  </div>
</form>
