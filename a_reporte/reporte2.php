<?php
  require_once("db_.php");
  $fecha=date("Y-m-d");
  $nuevafecha = strtotime ( '+0 month' , strtotime ( $fecha ) ) ;
  $fecha1 = date ( "Y-m-d" , $nuevafecha );

  $sql="select * from usuarios where idsucursal='".$_SESSION['idsucursal']."'";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();
  $res=$sth->fetchAll(PDO::FETCH_OBJ);

?>

<form id='consulta_avanzada' is='f-submit' des='a_reporte/reporte2_res' dix='resultado'  autocomplete='off'>
  <div class='container' >
    <div class="alert alert-light" role="alert">
      <h4 class="alert-heading">VENTAS POR VENDEDOR</h4>
      <div class='row'>
        <div class='col-xl col-auto'>
            <label><b>Del</b></label>
            <input class="form-control fechaclass" placeholder="Desde...." type="date" id='desde' name='desde' value='<?php echo $fecha1; ?>' autocomplete="off">
        </div>

        <div class='col-xl col-auto'>
          <label><b>Al</b></label>
          <input class="form-control fechaclass" placeholder="Hasta...." type="date" id='hasta' name='hasta' value='<?php echo $fecha; ?>' autocomplete="off">
        </div>

        <div class='col-xl col-auto'>
          <label><b>Vendedor</b></label>
          <select class="form-control" name="idusuario" id="idusuario">
            <option value=""></option>
          <?php
            foreach($res as $v2){
              echo "<option value='$v2->idusuario'>$v2->nombre</option>";
            }
          ?>
      </select>
        </div>


      </div>
      <hr>
      <div class='row'>
        <div class='col-xl col-auto'>
          <div class='btn-group'>
            <button title='Buscar' class='btn btn-warning btn-sm' id='buscar_canalizado' type='submit' id='lista_buscar'><i class='fa fa-search'></i><span> Buscar</span></button>
            <button type='button' class='btn btn-warning btn-sm'  id='print_persona' is='f-print' title='Editar' des='a_reporte/impventasprod' dix='resultado'><i class='fas fa-print'></i>Imprimir</button>
            <button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_reporte/index' dix='contenido' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div id='resultado'>

</div>
