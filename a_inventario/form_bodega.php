<?php
	require_once("db_.php");
	$idbodega=$_REQUEST['idbodega'];
	$idproducto=$_REQUEST['idproducto'];

  $bodega=$db->bodega_editar($idbodega);
  $fecha = new DateTime($bodega->fecha);
?>

<form is="f-submit" id="form_inventario" db="a_inventario/db_" fun="bodega_guardar" des="a_inventario/editar" desid='idproducto' cmodal='2'>
<input type="hidden" name="idbodega" id="idbodega" value="<?php echo $idbodega;?>">
<input type="hidden" name="idproducto" id="idproducto" value="<?php echo $idproducto;?>">
<div class='modal-header'>
	<h5 class='modal-title'>Editar bodega</h5>
</div>

<div class='modal-body' >

  <div class="row">
    <div class="col-xl col-auto">
      <label>Fecha:</label>
        <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" value="<?php echo $fecha->format('Y-m-d');?>" maxlength='100' placeholder="Fecha" required>
    </div>

    <div class="col-xl col-auto">
      <label>Hora:</label>
        <input type="time" class="form-control form-control-sm" name="hora" id="hora" value="<?php echo $fecha->format('H:i:s');?>" maxlength='100' placeholder="Hora" required>
    </div>
  </div>


</div>

<div class='modal-footer' >
  <div class='btn-group'>
    <?php
      if($_SESSION['nivel']==66){
        echo "<button class='btn btn-warning btn-sm' type='submit' is='f-submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>";
      }
    ?>
    <button class="btn btn-warning btn-sm" type="button" is="b-link" cmodal='1' ><i class="fas fa-sign-out-alt"></i>Cerrar</button>
  </div>
</div>
</form>
