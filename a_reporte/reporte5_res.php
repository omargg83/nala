<?php
  require_once("db_.php");
  $pd=$db->emitidasxsuc();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Ventas emitidas</h5>";
	echo "<hr>";
?>

  <div class="content table-responsive table-full-width">
  		<table id='x_venta' class='dataTable compact hover row-border' style='font-size:10pt;'>
  		<thead>
  		<tr>
  		<th>-</th>
  		<th>Venta</th>
  		<th>Fecha</th>
  		<th>Cliente</th>
  		<th>Sucursal</th>
  		<th>Total</th>
  		<th>Estado</th>
  		</tr>
  		</thead>
  		<tbody>
  		<?php
  			for($i=0;$i<count($pd);$i++){
  		?>
  					<tr id="<?php echo $pd[$i]['idventa']; ?>" class="edit-t">
  						<td>
  							<div class="btn-group">
                  <button class='btn btn-warning btn-sm'  id='edit_persona' is='b-link' id='nueva_venta' des='a_venta/venta' dix='trabajo'  v_idventa='<?php echo $pd[$i]['idventa']; ?> ' ><i class="fas fa-pencil-alt"></i></button>

  							</div>
  						</td>
  						<td  ><?php echo $pd[$i]["idventa"]; ?></td>
  						<td><?php echo $pd[$i]["fecha"]; ?></td>
  						<td><?php echo $pd[$i]["nombrecli"]; ?></td>
              <td><?php echo $pd[$i]["nombre"]; ?></td>
  						<td align="left"><?php echo number_format($pd[$i]["total"],2); ?></td>
  						<td><?php echo $pd[$i]["estado"]; ?></td>

  					</tr>
  		<?php
  			}
  		?>
  		</tbody>
  	</table>
  </div>


  <script>
  	$(document).ready( function () {
  		lista("x_venta");
  	});
  </script>
