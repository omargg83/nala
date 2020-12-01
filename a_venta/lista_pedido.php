<?php
	require_once("db_.php");

	if(isset($_REQUEST['idventa'])){
    $idventa=$_REQUEST['idventa'];

		$sql="select * from venta where idventa='$idventa'";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $venta=$sth->fetch(PDO::FETCH_OBJ);

		$sql="select sum(v_cantidad) as cantidad, sum(v_total_normal) as total,sum(v_total_mayoreo) as total_mayoreo,sum(v_total_distribuidor) as total_distribuidor from bodega where esquema=1 and idventa='$idventa' ";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $sumas=$sth->fetch(PDO::FETCH_OBJ);


    $estado_compra=$venta->estado;

		$sumcant=$sumas->cantidad;
		$sumtotmay=$sumas->total_mayoreo;
  }
  else{
    $idventa=0;
  }

		$pedido = $db->ventas_pedido($idventa);
		echo "<div class='tabla_css col-12' id='tabla_css' style='min-height:200px'>";
			echo "<div class='row header-row'>";
				echo "<div class='col-12'>DESCRIPCION</div>";
				echo "<div class='col-4'>#</div>";
				echo "<div class='col-4'>$</div>";
				echo "<div class='col-4'>G$</div>";
			echo "</div>";

			$gtotal=0;
		if($idventa>0){
			$total=0;

			foreach($pedido as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-12'>";
						echo "<div class='btn-group mr-3'>";
							if($estado_compra=="Activa"){
								echo "<button class='btn btn-warning btn-sm' id='del_$key->idbodega' type='button' is='is-borraprod' v_idbodega='$key->idbodega' title='Borrar'><i class='far fa-trash-alt'></i></button>";
							}
						echo "</div>";
						echo $key->nombre;
						echo $key->codigo;
					echo "</div>";

					echo "<div class='col-4 text-center'>";
						echo number_format($key->v_cantidad);
					echo "</div>";


					//////////// comparacion de esquemas de descuento /////////////////////////////
					if ( $key->esquema==0) {

							echo "<div class='col-4 text-right'>";
								echo number_format($key->v_precio_normal,2);
								$total=$key->v_precio_normal;

							echo "</div>";

					}

					if ( $key->esquema==2) {

						if ($key->v_cantidad < $key->mayoreo_cantidad) {
							echo "<div class='col-4 text-right'>";
								echo number_format($key->v_precio_normal,2);
								$total=$key->v_precio_normal;
							echo "</div>";
						}
						else if ($key->v_cantidad >= $key->mayoreo_cantidad and $key->v_cantidad < $key->distri_cantidad) {
							echo "<div class='col-4 text-right'>";
								echo number_format($key->v_precio_mayoreo,2);
								$total=$key->v_precio_mayoreo;
							echo "</div>";
						}

						else if ($key->v_cantidad >= $key->distri_cantidad) {
							echo "<div class='col-4 text-right'>";
								echo number_format($key->v_precio_distribuidor,2);
								$total=$key->v_precio_distribuidor;
							echo "</div>";
						}


					}


					else if ( $key->esquema==1) {


					if ($sumas->total_mayoreo < $key->monto_mayor and $sumcant < $key->cantidad_mayoreo){
					echo "<div class='col-4 text-right'>";
						echo number_format($key->v_precio_normal,2);
						$total=$key->v_precio_normal;
					echo "</div>";
					}
					else if ($sumas->total_distribuidor >= $key->monto_distribuidor) { //primero que nada checo que se alcance el monto para distribuidor antes de mayoreo porque si no no funciona
						echo "<div class='col-4 text-right'>";
							echo number_format($key->v_precio_distribuidor,2);
							$total=$key->v_precio_distribuidor;
						echo "</div>";
					}
					else if ($sumcant >= $key->cantidad_mayoreo or $sumas->total_mayoreo >= $key->monto_mayor) {
						echo "<div class='col-4 text-right'>";
							echo number_format($key->v_precio_mayoreo,2);
							$total=$key->v_precio_mayoreo;
						echo "</div>";
					}

					////////////// Actualiza el nuevo costo cuando las sumas de los productos alcanzan precio mayoreo o distribuidor
						$sql="update bodega set v_precio='$total' where idbodega='$key->idbodega' ";
						$sth = $db->dbh->prepare($sql);
						$sth->execute();
						$sth->fetch(PDO::FETCH_OBJ);

					/////////////// actualiza el costo total de la venta cuando las sumas de los productos alcanzan precio mayoreo o distribuidor

						$sql="select sum(v_precio * v_cantidad) as total from bodega where idventa='$idventa' ";
						$sth = $db->dbh->prepare($sql);
						$sth->execute();
						$rex=$sth->fetch(PDO::FETCH_OBJ);

						$totalt=$rex->total;
						$subtotalt=$totalt/1.16;
						$ivat=$totalt-$subtotalt;

						$sql="update venta set total='$rex->total',subtotal='$subtotalt', iva='$ivat' where idventa='$idventa' ";
						$sth = $db->dbh->prepare($sql);
						$sth->execute();
						$sth->fetch(PDO::FETCH_OBJ);

						///////////////////////////////////////

				}

					///////////////////////// fin comparacion esquemas


					echo "<div class='col-4 text-right'>";
						echo number_format($total*$key->v_cantidad,2);
						$total=$total*$key->v_cantidad;
						$gtotal+=$total;
					echo "</div>";
				echo "</div>";
			}

		}
		echo "</div>";

?>

<div class="row">
	<div class="col-sm-6">
		<p><b>Total final</b></p>
	</div>
	<div class="col-sm-6 text-right">
		<h5><?php echo moneda($gtotal); ?></h5>
	</div>
</div>
