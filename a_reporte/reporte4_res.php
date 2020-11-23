<?php
  require_once("db_.php");
  $pd=$db->corte_caja();
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
    </tr>
    </thead>
    <tbody>
    <?php
      foreach($pd as $key){
    ?>
          <tr>

            <td><?php echo $key->fecha; ?></td>
            <td align="left"><?php echo number_format($key->total,2); ?></td>
            <td><?php echo $key->tipo_pago; ?></td>

          </tr>
    <?php
      }
    ?>
    </tbody>
  </table>
</div>

  <script>
  	$(document).ready( function () {
  		lista("x_venta2");
  	});
  </script>
