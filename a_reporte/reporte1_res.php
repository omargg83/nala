<?php
  require_once("db_.php");
  $pd=$db->emitidas();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Ventas emitidas</h5>";
	echo "<hr>";
  echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
  ?>

  <div class='tabla_css' id='tabla_css'>
    <div class='row titulo-row'>
      <div class='col-xl col-auto'>
        VENTAS EMITIDAS
      </div>
    </div>
    <div class='row header-row'>
    <!--  <div class='col-1'>-</div> -->
      <div class='col-xl col-auto'>Ticket #</div>
      <div class='col-xl col-auto'>Fecha</div>
      <div class='col-xl col-auto'>Cliente</div>
      <div class='col-xl col-auto'>Tienda</div>
      <div class='col-xl col-auto'>Total</div>
      <div class='col-xl col-auto'>Estado</div>
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

            echo "<div class='col-xl col-auto text-center'>";
              echo $key->numero;
            echo "</div>";

            echo "<div class='col-xl col-auto'>".$key->fecha."</div>";


            echo "<div class='col-xl col-auto text-center'>";
              echo $key->nombrecli;
            echo "</div>";

            echo "<div class='col-xl col-auto text-center'>";
              echo $key->nombre;
            echo "</div>";

            echo "<div class='col-xl col-auto text-right' >".moneda($key->total)."</div>";
            $monto_t+=$key->total;
            echo "<div class='col-xl col-auto text-center'>";
              echo $key->estado;
            echo "</div>";

          echo '</div>';
        }

        echo "<div class='row body-row' draggable='true'>";
            echo "<tr>";
            echo "<td>Total  </td>";
            echo "<div class='col-xl col-auto text-right' ><b>".moneda($monto_t)."</b></div>";
            echo"</tr>";
        echo'</div>';
      ?>
    </div>
  </div>
