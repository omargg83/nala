<?php
	require_once("db_.php");
  $id=$_REQUEST['id'];
  $resp=$db->info($id);

	$cliente=$db->cliente($resp->idcliente);
  $tipo="";

 ?>
  <div class="modal-content">
     <div class="modal-header">
       <h5 class="modal-title">Cita</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>
     <div class="modal-body">
        <div class='row'>
          <div class='col-2'>
            <label>Cita</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->idcitas; ?>' readonly>
          </div>
          <div class='col-5'>
            <label>Fecha</label>
            <input id='fecha' name='fecha' class='form-control form-control-sm' value='<?php echo fecha($resp->fecha,2); ?>' readonly>
          </div>
          <div class='col-5'>
            <label>Hasta</label>
            <input id='fecha' name='fecha' class='form-control form-control-sm' value='<?php echo fecha($resp->fecha_fin,2); ?>' readonly>
          </div>
        </div>
			  <div class='row'>
					<div class='col-3'>
            <label>Estatus</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->estatus; ?>' readonly>
          </div>
					<div class='col-3'>
            <label>Cubículo</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->cubiculo; ?>' readonly>
          </div>
					<div class='col-3'>
            <label>Servicio</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->servicio; ?>' readonly>
          </div>
					<div class='col-3'>
            <label>Precio</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->precio; ?>' readonly>
          </div>
        </div>
				<div class='row'>
					<div class='col-6'>
            <label>Nombre</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $cliente->nombre; ?>' readonly>
          </div>
        </div>
     </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-outline-primary btn-sm" onclick='editar_cita(<?php echo $id; ?>)'><i class='fas fa-pencil-alt'></i>Editar</button>
       <button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal"><i class='fas fa-undo-alt'></i>Cerrar</button>
     </div>
   </div>
