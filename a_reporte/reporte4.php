<?php
  require_once("db_.php");
  $fecha=date("Y-m-d");
  $nuevafecha = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
  $fecha1 = date ( "Y-m-d" , $nuevafecha );


?>

<form id='consulta_avanzada' is='f-submit' des='a_reporte/reporte4_res' dix='resultado'  autocomplete='off'>
  <div class='container' >
    <div class="alert alert-light" role="alert">
      <h4 class="alert-heading">CORTE DE CAJA</h4>
      <div class='row'>
        <div class='col-sm-3'>
            <label><b>Del</b></label>
            <input class="form-control fechaclass" placeholder="Desde...." type="date" id='desde' name='desde' value='<?php echo $fecha1; ?>' autocomplete="off">
        </div>

        <div class='col-sm-3'>
          <label><b>Al</b></label>
          <input class="form-control fechaclass" placeholder="Hasta...." type="date" id='hasta' name='hasta' value='<?php echo $fecha; ?>' autocomplete="off">
        </div>


      </div>
      <hr>
      <div class='row'>
        <div class='col-sm-4'>
          <div class='btn-group'>
            <button title='Buscar' class='btn btn-outline-warning btn-sm' id='buscar_canalizado' type='submit' id='lista_buscar'><i class='fa fa-search'></i><span> Consultar</span></button>
            <button type='button' class='btn btn-warning btn-sm mr-2'  id='print_persona' is='b-print' title='Editar' des='a_reporte/corte' dix='resultado' ><i class='fas fa-print'></i>Imprimir</button>

          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div id='resultado'>

</div>
