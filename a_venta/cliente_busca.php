  <div class='modal-header'>
  	<h5 class='modal-title'>Agregar cliente</h5>
  </div>
  <div class="modal-body" style='max-height:580px;overflow: auto;'>
    <div clas='row'>
			<form is="b-submit" id="form_cliex" des="a_venta/cliente_lista" dix='lista_clientesx' >
	      <div class="input-group mb-3">
	        <input type="text" class="form-control" name="texto" id='texto' placeholder='buscar cliente' aria-label="buscar cliente" aria-describedby="basic-addon2" >
	        <div class="input-group-append">
	          <button class="btn btn-warning btn-sm" type="submit"><i class='fas fa-search'></i>Buscar</button>
	        </div>
	      </div>
			</form>
    </div>
    <div clas='row' id='lista_clientesx'>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-warning btn-sm" is='b-link' cmodal='1'><i class="fas fa-sign-out-alt"></i>Cerrar</button>
  </div>
