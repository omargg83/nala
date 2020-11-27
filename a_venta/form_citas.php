<?php
	$idventa=$_REQUEST['idventa'];
?>

<form is="b-submit" id="form_busca" des="a_venta/busca_cita" dix='resultadosx' >
<?php
	echo "<input  type='hidden' id='idventa' NAME='idventa' value='$idventa'>";
?>

<div class="modal-header">
  <h5 class="modal-title">Buscar citas</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<div class="modal-body" style='max-height:580px;overflow: auto;'>
	<div clas='row'>
			<div class="input-group mb-3">
			<input type="text" class="form-control" name="texto_cita" id='texto_cita' placeholder='buscar cita' aria-label="buscar producto" aria-describedby="basic-addon2" >
			<div class="input-group-append">
				<button class="btn btn-warning btn-sm" type="submit"><i class='fas fa-search'></i>Buscar</button>
			</div>
		</div>
	</div>
	<div clas='row' id='resultadosx'>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i>Cerrar</button>
</div>

</form>
