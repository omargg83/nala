<?php
	require_once("db_.php");

	if(isset($_REQUEST['idtraspaso'])){
    $idtraspaso=$_REQUEST['idtraspaso'];

		$sql="select * from traspasos where idtraspaso='$idtraspaso'";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $venta=$sth->fetch(PDO::FETCH_OBJ);
    $estado_compra=$venta->estado;

  }
  else{
    $idtraspaso=0;
  }

		$pedido = $db->traspaso_pedido($idtraspaso);
		echo "<div class='tabla_css col-12' id='tabla_css'>";
			echo "<div class='row header-row'>";
				echo "<div class='col-8'>DESCRIPCION</div>";
				echo "<div class='col-4'>#</div>";

			echo "</div>";


		if($idtraspaso>0){
			$total=0;
			foreach($pedido as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6'>";
						echo "<div class='btn-group mr-3'>";
							if($estado_compra=="Activa"){
								echo "<button class='btn btn-warning btn-sm' id='del_$key->idbodega' type='button' is='t-borraprod' v_idbodega='$key->idbodega' title='Borrar'><i class='far fa-trash-alt'></i></button>";
							}
						echo "</div>";

						echo $key->nombre;
					echo "</div>";

					echo "<div class='col-2 col-sm-4 col-md-4 col-lg-4 col-xl-2 text-center'>";
						echo number_format($key->v_cantidad);
					echo "</div>";

				echo "</div>";
			}

		}
		echo "</div>";
?>
