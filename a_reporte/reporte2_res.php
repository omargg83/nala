<?php
  require_once("db_.php");
  $pd=$db->productos_vendidos();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Reporte por vendedor</h5>";
  echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
  ?>

  <div class='tabla_css' id='tabla_css'>
    <div class='row titulo-row'>
      <div class='col-12'>
        VENTAS Y PRODUCTOS POR VENDEDOR
      </div>
    </div>
    <div class='row header-row'>
    <!--  <div class='col-1'>-</div> -->
      <div class='col-1'>Ticket #</div>
      <div class='col-2'>Fecha</div>
      <div class='col-3'>Producto</div>
      <div class='col-2'>Cantidad</div>
      <div class='col-1'>Precio U.</div>
      <div class='col-1'>Total</div>
      <div class='col-1'>Estado</div>
      <div class='col-1'>Vendedor</div>
    </div>

      <?php
      $monto_t=0;
        foreach($pd as $key){
          echo "<div class='row body-row' draggable='true'>";
          /*  echo "<div class='col-1'>";
              echo "<div class='btn-group'>";

              echo "<button class='btn btn-warning btn-sm'  id='edit_persona' is='b-link' id='nueva_venta' des='a_venta/venta' dix='trabajo' title='Ver detalle' v_idventa='$key->idventa' ><i class='far fa-eye'></i></button>";
              //////
              echo "</div>";
            echo "</div>";*/

            echo "<div class='col-1 text-center'>";
              echo $key->idventa;
            echo "</div>";

            echo "<div class='col-2'>".$key->fecha."</div>";


            echo "<div class='col-3 text-center'>";
              echo $key->nombre;
            echo "</div>";

            echo "<div class='col-2 text-center'>";
              echo $key->v_cantidad;
            echo "</div>";

            echo "<div class='col-1 text-right' >".moneda($key->v_precio)."</div>";
            echo "<div class='col-1 text-right' >".moneda($key->v_cantidad*$key->v_precio)."</div>";
            $monto_t+=($key->v_cantidad*$key->v_precio);
            echo "<div class='col-1 text-center'>";
              echo $key->estado;
            echo "</div>";

            echo "<div class='col-1 text-center'>";
              echo $key->vendedor;
            echo "</div>";

          echo '</div>';
        }
        echo "<div class='row body-row' draggable='true'>";
            echo "<tr>";
            echo "<td>Total  </td>";
            echo "<div class='col-1 text-right' ><b>".moneda($monto_t)."</b></div>";
            echo"</tr>";
        echo'</div>';
      ?>
    </div>
  </div>
