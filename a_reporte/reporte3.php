<?php
  require_once("db_.php");
  $fecha=date("Y-m-d");
  $nuevafecha = strtotime ( '+0 month' , strtotime ( $fecha ) ) ;
  $fecha1 = date ( "Y-m-d" , $nuevafecha );

?>

<form id='consulta_avanzada' is='f-submit' des='a_reporte/reporte3_res' dix='resultado'  autocomplete='off'>
  <div class='container' >
    <div class="alert alert-light" role="alert">
      <h4 class="alert-heading">CORTE DE CAJA</h4>
      <div class='row'>
        <div class='col-5'>
            <label><b>Del</b></label>
            <input class="form-control fechaclass" placeholder="Desde...." type="date" id='desde' name='desde' value='<?php echo $fecha1; ?>' autocomplete="off">
        </div>

        <div class='col-5'>
          <label><b>Al</b></label>
          <input class="form-control fechaclass" placeholder="Hasta...." type="date" id='hasta' name='hasta' value='<?php echo $fecha; ?>' autocomplete="off">
        </div>

      </div>
      <hr>
      <div class='row'>
        <div class='col-12'>
          <div class='btn-group'>
            <button title='Buscar' class='btn btn-warning btn-sm' id='buscar_canalizado' type='submit' id='lista_buscar'><i class='fa fa-search'></i><span> Consultar</span></button>

            <button type='button' class='btn btn-warning btn-sm'  id='print_persona' is='f-print' title='Editar' des='a_reporte/corte' dix='resultado'><i class='fas fa-print'></i>Imprimir</button>

            <button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_reporte/index' dix='contenido' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>

          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div id='resultado'>

</div>
