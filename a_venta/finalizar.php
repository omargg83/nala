<?php
require_once("db_.php");
$idventa=$_REQUEST['idventa'];

$total=$db->suma_venta($idventa);
if ($total>0){

}
else{
  echo "<div class='card'>";
  echo "<br><center>Debe agregar un producto</center>";
  echo "<div class='card-body'>";
  echo "</div>";
  echo "<div class='card-footer'>";
  echo "<button type='button' class='btn btn-warning btn-sm' data-dismiss='modal'><i class='fas fa-sign-out-alt'></i>Cancelar</button>";
  echo "</div>";
  echo "</div>";
  exit();
}

$total=round($total,2);
?>

<form id="form_finalizar" is='is-totalv' db="a_venta/db_" fun="finalizar_venta" des='a_venta/venta' desid='idventa' dix='trabajo' cmodal='2'>


  <div class="modal-header">
    <h5 class="modal-title">Finalizar venta</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="modal-body" style='max-height:580px;overflow: auto;'>
    <div clas='row'>
      <div class='col-xl col-auto'>
        <label>Metodo de pago</label>
        <select class="form-control" name="tipo_pago" id="tipo_pago">
          <option value="Efectivo">Efectivo</option>
          <option value="Debito">Tarjeta de debito</option>
          <option value="T.Credito">Tarjeta de Credito</option>
          <option value="Transferencia">Transferencia Electronica</option>
          <option value="Deposito">Deposito</option>
          <option value="Cupon">Cupón</option>
        </select>
      </div>


      <div class='col-xl col-auto'>
        <label>Total</label>
        <input type='text' name='total_g' id='total_g' style='text-align:right' placeholder='Total' value='<?php echo $total; ?>' class='form-control' readonly>
      </div>

      <div class='col-xl col-auto'>
        <label>Recibido</label>
        <input type='text' name='efectivo_g' id='efectivo_g' style='text-align:right' placeholder='Recibido' value='' class='form-control' required onchange='cambio_total()'>
      </div>

      <div class='col-xl col-auto'>
        <label>Cambio</label>
        <input type='text' name='cambio_g' id='cambio_g' style='text-align:right' placeholder='Cambio' value='' class='form-control' required readonly>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-cash-register"></i>Finalizar</button>
    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i>Cancelar</button>
  </div>
</form>
