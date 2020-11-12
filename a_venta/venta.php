<?php
  require_once("db_.php");
  $estado="nuevo";
  echo "<div id='trabajo'>";
  if(isset($_REQUEST['idventa'])){
    $idventa=$_REQUEST['idventa'];
    $estado="editar";

    $sql="select * from venta where idventa='$idventa'";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $venta=$sth->fetch(PDO::FETCH_OBJ);
    $numero_compra=$venta->numero;
    $estado_compra=$venta->estado;

    $fecha1 = date ( "Y-m-d" , strtotime($venta->fecha) );
    $fecha_compra=$fecha1;

    $idcliente=$venta->idcliente;
    $sql="select * from clientes where idtienda='".$_SESSION['idtienda']."' and idcliente='$idcliente'";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $cliente=$sth->fetch(PDO::FETCH_OBJ);
    $idcliente=$cliente->idcliente;
    $n_cliente=$cliente->nombre;
  }
  else{
    $idventa=0;
    $idcliente=0;
    $numero_compra=0;
    $total=0;
    $fecha_compra=date ( "Y-m-d" );
    $estado_compra="Activa";

    $sql="select * from clientes where idtienda='".$_SESSION['idtienda']."' limit 1";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $cliente=$sth->fetch(PDO::FETCH_OBJ);
    $idcliente=$cliente->idcliente;
    $n_cliente=$cliente->nombre;
  }
?>
<input type="hidden" name="idventa" id="idventa" value="<?php echo $idventa; ?>" readonly>
  <div class="container-fluid">
  	<div class='card'>
      <div class='card-header'>Venta #<?php echo $numero_compra; ?></div>
      <div class='card-body'>
        <div class='row'>
          <div class='col-sm-12 col-md-12 col-lg-7 col-xl-7'>
            <div class='row mb-3' id='dato_compra'>
              <?php
                include 'dato_compra.php';
              ?>
            </div>
            <div class='row mb-3' id='cliente_datos'>
              <?php
                include 'cliente_datos.php';
              ?>
            </div>
            <?php
              if($estado_compra=="Activa"){
                if($_SESSION['a_sistema']==1){
                  echo "<button class='btn btn-warning btn-sm mr-2' type='button' is='b-link' des='a_venta/cliente_busca' dix='trabajo' omodal='1'><i class='fas fa-user-tag'></i>Cliente</button>";

                  echo "<button type='button' class='btn btn-warning btn-sm mr-2' id='producto_add' is='b-link' v_idventa='$idventa' des='a_ventas/form_citas' omodal='1' title='Agregar cita'><i class='far fa-calendar-check'></i>Citas</button>";

                  echo "<button type='button' class='btn btn-warning btn-sm mr-2' id='finalizar' is='is-finalizar'><i class='fas fa-cash-register'></i>Finalizar</button>";
                }
              }
              else{
                if($_SESSION['a_sistema']==1){
                  echo "<button type='button' class='btn btn-warning btn-sm mr-2' id='nueva' is='b-link' des='a_venta/venta' dix='trabajo'><i class='fas fa-cash-register'></i>Nueva</button>";

                  echo "<button type='button' class='btn btn-warning btn-sm mr-2'  id='print_persona' is='b-print' title='Editar' des='a_venta/imprimir' dix='trabajo' v_idventa='$idventa'><i class='fas fa-print'></i>Imprimir</button>";
                }
              }

            ?>
            <hr>
            <div class='row' >
              <div class='col-12' id='lista' style='min-height:300px; overflow:auto;'>
                <?php
                  include 'lista_pedido.php';
                ?>
              </div>
            </div>
          </div>
          <hr>
          <div class='col-sm-12 col-md-12 col-lg-5 col-xl-5'>
            <div class='row'>
              <div class='col-12'>

                  <div class="col-sm-12">
                    <form is="p-busca" id="form_busca" >
                			<div clas='row'>
                					<div class="input-group mb-3">
                					<input type="text" class="form-control form-control-sm" name="prod_venta" id='prod_venta' placeholder='buscar producto' aria-label="buscar producto" aria-describedby="basic-addon2">
                					<div class="input-group-append">
                          	<button class='btn btn-warning btn-sm' type='submit' ><i class='fas fa-search'></i>Buscar</button>
                					</div>
                				</div>
                			</div>
                		</form>
                  </div>

              </div>
            </div>
            <hr>
            <div clas='row' id='resultadosx' style='min-height:500px; max-height: 500; overflow:auto;'>
            </div>
            <?php
              include 'producto_buscar.php';
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
