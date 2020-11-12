<?php
	require_once("db_.php");

	if(isset($_REQUEST['idventa'])){
    $idventa=$_REQUEST['idventa'];

		$sql="select * from venta where idventa='$idventa'";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $venta=$sth->fetch(PDO::FETCH_OBJ);
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

					echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
						echo number_format($key->v_precio,2);
					echo "</div>";

					echo "<div class='col-5 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-right'>";
						echo number_format($key->v_total,2);
						$total+=$key->v_total;
					echo "</div>";
				echo "</div>";
			}

		}
		echo "</div>";
?>
