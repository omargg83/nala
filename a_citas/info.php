<?php
	require_once("db_.php");
  $id=$_REQUEST['id'];
  $resp=$db->info($id);

	$cliente=$db->cliente($resp->idcliente);
  $tipo="";

 ?>
  <div class="modal-content">
     <div class="modal-header">
       <h5 class="modal-title">Cita ó Agenda</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>
     <div class="modal-body">
        <div class='row'>
          <div class='col-xl col-auto'>
            <label>No.</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->idcitas; ?>' readonly>
          </div>
          <div class='col-xl col-auto'>
            <label>Asunto</label>
            <input id='asunto' name='asunto' class='form-control form-control-sm' value='<?php echo $resp->asunto; ?>' readonly>
          </div>
        </div>
				<hr>
				<div class='row'>
					<div class='col-xl col-auto'>
            <label>Fecha</label>
            <input id='fecha' name='fecha' class='form-control form-control-sm' value='<?php echo fecha($resp->fecha,2); ?>' readonly>
          </div>
          <div class='col-xl col-auto'>
            <label>Hasta</label>
            <input id='fecha_fin' name='fecha_fin' class='form-control form-control-sm' value='<?php echo fecha($resp->fecha_fin,2); ?>' readonly>
          </div>
        </div>
				<hr>
			  <div class='row'>
					<div class='col-xl col-auto'>
            <label>Estatus</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->estatus; ?>' readonly>
          </div>
					<div class='col-xl col-auto'>
            <label>Cubículo</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->cubiculo; ?>' readonly>
          </div>
					<div class='col-xl col-auto'>
            <label>Servicio</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->servicio; ?>' readonly>
          </div>
					<div class='col-xl col-auto'>
            <label>Precio</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $resp->precio; ?>' readonly>
          </div>
        </div>
				<hr>
				<?php	if ($resp->idcliente>0){?>
				<div class='row'>
					<div class='col-xl col-auto'>
            <label>Nombre Cliente</label>
            <input id='id' name='id' class='form-control form-control-sm' value='<?php echo $cliente->nombre; ?>' readonly>
          </div>
        </div>

			<?php }	?>
     </div>
     <div class="modal-footer">

			<?php	echo "<button type='button' class='btn btn-outline-primary btn-sm' id='edit_comision' is='b-link' title='Editar' des='a_citas/editar' dix='trabajo' v_idcita='$resp->idcitas' data-dismiss='modal'><i class='fas fa-pencil-alt'></i>Editar</button>";?>
       	<button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal"><i class='fas fa-undo-alt'></i>Cerrar</button>
     </div>
   </div>
