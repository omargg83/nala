<?php
	require_once("db_.php");

	if(isset($_REQUEST['idventa'])){
    $idventa=$_REQUEST['idventa'];

		$sql="select * from venta where idventa='$idventa'";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $venta=$sth->fetch(PDO::FETCH_OBJ);

		$sql="select count(v_cantidad) as cantidad, sum(v_total_normal) as total,sum(v_total_mayoreo) as total_mayoreo,sum(v_total_distribuidor) as total_distribuidor from bodega where esquema=1 and idventa='$idventa' ";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $sumas=$sth->fetch(PDO::FETCH_OBJ);


    $estado_compra=$venta->estado;

  }
  else{
    $idventa=0;
  }

		$pedido = $db->ventas_pedido($idventa);
		echo "<div class='tabla_css col-12' id='tabla_css'>";
			echo "<div class='row header-row'>";
				echo "<div class='col-6'>DESCRIPCION</div>";
				echo "<div class='col-2'>#</div>";
				echo "<div class='col-2'>$</div>";
				echo "<div class='col-2'>G$</div>";
			echo "</div>";


		if($idventa>0){
			$total=0;
			foreach($pedido as $key){
				print_r($sumas);
				print_r($key);
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6'>";
						echo "<div class='btn-group mr-3'>";
							if($estado_compra=="Activa"){
								echo "<button class='btn btn-warning btn-sm' id='del_$key->idbodega' type='button' is='is-borraprod' v_idbodega='$key->idbodega' title='Borrar'><i class='far fa-trash-alt'></i></button>";
							}
						echo "</div>";

						echo $key->nombre;
					echo "</div>";

					echo "<div class='col-2 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-center'>";
						echo number_format($key->v_cantidad);
					echo "</div>";


					//////////// comparacion de esquemas de descuento /////////////////////////////
					if ( $key->esquema==0) {

							echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
								echo number_format($key->v_total_normal,2);
							echo "</div>";

					}

					if ( $key->esquema==2) {

						if ($key->v_cantidad < $key->mayoreo_cantidad) {
							echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
								echo number_format($key->v_total_normal,2);
							echo "</div>";
						}
						else if ($key->v_cantidad >= $key->mayoreo_cantidad and $key->v_cantidad < $key->distri_cantidad) {
							echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
								echo number_format($key->v_total_mayoreo,2);
							echo "</div>";
						}

						else if ($key->v_cantidad >= $key->distri_cantidad) {
							echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
								echo number_format($key->v_total_distribuidor,2);
							echo "</div>";
						}


					}


					else if ( $key->esquema==1) {


					if ($sumas->total_mayoreo<1000){
					echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
						echo number_format($key->v_total_normal,2);
					echo "</div>";
					}
					else if ($sumas->total_mayoreo>=1000) {
						echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
							echo number_format($key->v_total_mayoreo,2);
						echo "</div>";
					}
					else if ($sumas->total_mayoreo>=3000 ) {
						echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
							echo number_format($key->v_total_distribuidor,2);
						echo "</div>";
					}

				}

					///////////////////////// fin comparacion esquemas


					echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
						echo number_format($key->v_total,2);
						$total+=$key->v_total;
					echo "</div>";
				echo "</div>";
			}

		}
		echo "</div>";
?>
