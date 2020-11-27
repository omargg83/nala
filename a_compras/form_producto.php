<?php
	require_once("db_.php");
	$idcompra=$_REQUEST['idcompra'];
?>
	<div class="modal-header">
	  <h5 class="modal-title">Buscar producto</h5>
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>

	<div class="modal-body" style='max-height:580px;overflow: auto;'>
		<form is="b-submit" id="form_agrega" des="a_compras/busca_producto" dix='productos' >
			<input  type='hidden' id='idcompra' NAME='idcompra' value='<?php echo $idcompra; ?>'>
			<div clas='row'>
					<div class="input-group mb-3">
					<input type="text" class="form-control" name="prod_venta" id='prod_venta' placeholder='buscar producto' aria-label="buscar producto" aria-describedby="basic-addon2">
					<div class="input-group-append">
						<button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i>Buscar</button>
						<button type="button" class="btn btn-warning btn-sm" is='b-link' cmodal='1'><i class="fas fa-sign-out-alt"></i>Cerrar</button>
					</div>
				</div>
			</div>
		</form>
		<div clas='row' id='productos'>

		</div>
	</div>
