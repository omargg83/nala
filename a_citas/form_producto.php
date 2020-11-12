

	<?php
	require_once("db_.php");
	$idcita=$_REQUEST['idcita'];
	$idproducto=$_REQUEST['idproducto'];

	?>

		<div class="modal-header">
		  <h5 class="modal-title">Buscar producto</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>

		<div class="modal-body" style='max-height:580px;overflow: auto;'>
			<form is="b-submit" id="form_busca" des="a_citas/productos_lista" dix='resultadosx' >
				<input  type='hidden' id='idventa' NAME='idventa' value='<?php echo $id; ?>'>

				<div clas='row'>
						<div class="input-group mb-3">
						<input type="text" class="form-control" name="prod_venta" id='prod_venta' placeholder='buscar producto' aria-label="buscar producto" aria-describedby="basic-addon2" onkeyup='Javascript: if (event.keyCode==13) buscar_prodpedido(<?php echo $idcita;  ?>)'>
						<div class="input-group-append">
							<button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i>Buscar</button>
						</div>
					</div>
				</div>
			</form>
			<div clas='row' id='resultadosx'>

			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-warning btn-sm" is='b-link' cmodal='1'><i class="fas fa-sign-out-alt"></i>Cerrar</button>
		</div>
