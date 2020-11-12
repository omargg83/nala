<?php
	require_once("db_.php");
	$idcita=$_REQUEST['idcita'];

	$sql="select * from bodega where idcitas='$idcita' order by id desc";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$pedido=$sth->fetchAll();
	$subtotal=0;
	$iva=0;
	$gtotal=0;

	echo "<div id='tablax'>";
		echo "<div class='row' >";
			echo "<div class='col-1'>";
				echo "--";
			echo "</div>";
			echo "<div class='col-2'>";
				echo "<b>Código</b>";
			echo "</div>";
			echo "<div class='col-3'>";
				echo "<B>NOMBRE</B>";
			echo "</div>";

			echo "<div class='col-2 text-center'>";
				echo "<B>CANTIDAD</B>";
			echo "</div>";
			echo "<div class='col-2 text-right'>";
				echo "<B>PRECIO</B>";
			echo "</div>";
			echo "<div class='col-2 text-right'>";
				echo "<B>TOTAL</B>";
			echo "</div>";
		echo "</div>";
		if($id>0){
			foreach($pedido as $key){
				$sql="SELECT * from productos where id=:id";
				$sth = $db->dbh->prepare($sql);
				$sth->bindValue(":id",$key['idproducto']);
				$sth->execute();
				$res=$sth->fetch(PDO::FETCH_OBJ);

				echo "<div class='row' id='div_".$key['id']."'>";
					echo "<div class='col-1'>";

						echo "<button class='btn btn-outline-primary btn-sm' id='eliminar_pedido' data-lugar='a_citas/db_' data-destino='a_citas/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_venta' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";

					echo "</div>";
					echo "<div class='col-2'>";
						echo $key['codigo'];
					echo "</div>";

					echo "<div class='col-3'>";
						echo $key['nombre'];
						if ($key['tipo']==4){
							echo "<br><span style='font-size:12px;'><b>Marca:</b> ".$res->marca."</span>";
							echo "<br><span style='font-size:12px;'><b>Modelo:</b> ".$res->modelo."</span>";
							echo "<br><span style='font-size:12px;'><b>IMEI:</b> ".$res->imei."</span>";
						}
						if(strlen($key['observaciones'])>0){
							echo "<br><span style='font-size:12px;'><b>Observacion:</b> ".$key['observaciones']."</span>";
						}
						if(strlen($key['cliente'])>0){
							echo "<br><span style='font-size:12px;'><b>Cliente:</b> ".$key['cliente']."</span>";
						}
					echo "</div>";

					echo "<div class='col-2 text-center'>";
						echo number_format($key['v_cantidad']);
					echo "</div>";

					echo "<div class='col-2 text-right'>";
						echo number_format($key['v_precio'],2);
					echo "</div>";

					$gtotal=$key['v_total'];
					echo "<div class='col-2 text-right'>";
						echo number_format($key['v_total'],2);
					echo "</div>";
				echo "</div>";
				echo "<hr>";
			}
		}

		$values = array('total'=>$gtotal);
		$db->update('citas',array('idcitas'=>$id), $values);


?>
<div class='row'>
	<div class='col-10 text-right'>
		<b>TOTAL $</b>
	</div>
	<div class='col-2'>
		<input class="form-control" id="total_x" name="total_x" value='<?php echo number_format($gtotal,2); ?>' disabled readonly style="direction: rtl;" />
	</div>
</div>
