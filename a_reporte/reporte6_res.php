<?php
  require_once("db_.php");
  $pd=$db->corte_cajaxsuc();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Corte de caja</h5>";
	echo "<hr>";

?>

<div class="content table-responsive table-full-width">
    <table id='x_venta2' class='dataTable compact hover row-border' style='font-size:10pt;'>
    <thead>
    <tr>
      <th>Fecha</th>
      <th>Total</th>
      <th>Tipo de Pago</th>
      <th>Sucursal</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $monto_t=0;
      foreach($pd as $key){
    ?>
          <tr>

            <td><?php echo $key->fecha; ?></td>
            <td align="left"><?php echo number_format($key->total,2);
            $monto_t+=$key->total ?></td>
            <td><?php echo $key->tipo_pago; ?></td>
            <td><?php echo $key->nombre; ?></td>

          </tr>
    <?php
      }
    ?>

    <tr>
            <td>Total</td>
            <td align='left'><b>  <?php echo moneda($monto_t) ?></b></td>
            </tr>

    </tbody>
  </table>
</div>

  <script>
  	$(document).ready( function () {
  		lista("x_venta2");
  	});
  </script>
