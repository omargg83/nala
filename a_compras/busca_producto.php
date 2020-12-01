<?php
	require_once("db_.php");
  $texto=$_REQUEST['prod_venta'];
  $idcompra=$_REQUEST['idcompra'];

  $sql="SELECT * from productos_catalogo where idtienda=".$_SESSION['idtienda']." and tipo=3 and (nombre like '%$texto%' or  codigo like '%$texto%') order by tipo limit 10";
  $sth = $db->dbh->prepare($sql);
  $sth->bindValue(":texto","%$texto%");
  $sth->execute();
  $res=$sth->fetchAll(PDO::FETCH_OBJ);

	echo "<div class='tabla_css' id='tabla_css'>";
		echo "<div class='row header-row'>";
			echo "<div class='col-xl col-auto'>#</div>";
			echo "<div class='col-xl col-auto'>Código</div>";
			echo "<div class='col-xl col-auto'>Nombre</div>";
		echo "</div>";

  if(count($res)>0){
    foreach ($res as $key) {
			echo "<div class='row body-row' draggable='true'>";
				echo "<div class='col-xl col-auto'>";
		      echo  "<div class='btn-group'>";
		      if($key->tipo==3){
		        echo "<button type='button' is='b-link' id='sel_producto_$key->idcatalogo' des='a_compras/selecciona_producto' dix='productos' v_idcatalogo='$key->idcatalogo' v_idcompra='$idcompra' class='btn btn-warning btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
		      }
		      echo  "</div>";
      echo  "</div>";

      echo "<div class='col-xl col-auto'>";
				echo  $key->codigo;
      echo  "</div>";

      echo "<div class='col-xl col-auto'>";
      echo  $key->nombre;
      echo  "</div>";

      echo  "</div>";
    }
  }
	echo "</div>";
?>
