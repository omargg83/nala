<?php
  require_once("db_.php");

  $sql="select * from sucursal where idtienda='".$_SESSION['idtienda']."' and idsucursal!='".$_SESSION['idsucursal']."'";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();
  $res=$sth->fetchAll(PDO::FETCH_OBJ);
?>

<form id='consulta_avanzada' is='f-submit' des='a_supervisor/lista' dix='resultado'  autocomplete='off'>
  <div class='container' >
    <div class="alert alert-light" role="alert">
      <h4 class="alert-heading">INVENTARIO POR SUCURSAL</h4>
      <div class='row'>

        <div class='col-xl col-auto'>
          <label><b>Sucursal</b></label>
          <select class="form-control" name="idsucursal" id="idsucursal" required>
            <option value=""></option>
          <?php
            foreach($res as $v2){
              echo "<option value='$v2->idsucursal'>$v2->nombre</option>";
            }
          ?>
      </select>
        </div>

      </div>
      <hr>
      <div class='row'>
        <div class='col-xl col-auto'>
          <div class='btn-group'>

            <button title='Buscar' class='btn btn-warning btn-sm' id='buscar_canalizado' type='submit' id='lista_buscar'><i class='fa fa-search'></i><span>Consultar</span></button>

            <button type='button' class='btn btn-warning btn-sm'  id='excel' is='a-link' title='Excel' des='a_supervisor/excel' dix='resultado' v_idsucursal='  <?php echo " $v2->idsucursal"?>'><i class="far fa-file-excel"></i>Excel</button>

            <button type='button' class='btn btn-warning btn-sm' id='lista_cat' is='b-link'  des='a_supervisor/index' dix='contenido' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>

          </div>
        </div>
      </div>

    </div>
  </div>
</form>

<div id='resultado'>

</div>
