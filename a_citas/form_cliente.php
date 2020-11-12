
	<?php
		require_once("db_.php");
		$idcliente=$_REQUEST['idcliente'];
		$idcita=$_REQUEST['idcita'];
	?>
	<form is="b-submit" id="form_busca" des="a_citas/buscar_clientev" dix='resultadosx' >

	<?php
	echo "<input  type='hidden' id='idcliente' NAME='idcliente' value='$idcliente'>";
	echo "<input  type='hidden' id='idcita' NAME='idcita' value='$idcita'>";
	?>
	  <div class='modal-header'>
	  	<h5 class='modal-title'>Agregar cliente</h5>
	  </div>
	  <div class="modal-body" style='max-height:580px;overflow: auto;'>
	    <div clas='row'>
	      <div class="input-group mb-3">
	        <input type="text" class="form-control" name="texto" id='texto' placeholder='buscar cliente' aria-label="buscar cliente" aria-describedby="basic-addon2" >
	        <div class="input-group-append">
	          <button class="btn btn-warning btn-sm" type="submit"><i class='fas fa-search'></i>Buscar</button>
	        </div>
	      </div>
	    </div>
	    <div clas='row' id='resultadosx'>

	    </div>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-warning btn-sm" is='b-link' cmodal='1'><i class="fas fa-sign-out-alt"></i>Cerrar</button>
	  </div>
	</form>
