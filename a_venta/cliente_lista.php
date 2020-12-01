<?php
	require_once("db_.php");

  $texto=$_REQUEST['texto'];

  $sql="SELECT * from clientes where nombre like '%$texto%' limit 100";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();

	echo "<div class='tabla_css' id='tabla_css'>";
		echo "<div class='row header-row'>";
			echo "<div class='col-xl col-auto'>-</div>";
			echo "<div class='col-xl col-auto'>Correo</div>";
			echo "<div class='col-xl col-auto'>Telefono</div>";
		echo "</div>";

  foreach($sth->fetchAll(PDO::FETCH_OBJ) as $key){
		echo "<div class='row body-row' is='b-card' draggable='true'>";
			echo  "<div class='col-xl col-auto'>";
				echo "<div class='btn-group mr-3'>";
	        echo "<button type='button' is='is-cliente' v_idcliente='$key->idcliente' cmodal='2' class='btn btn-warning btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
					echo "</div>";
          echo $key->nombre;
      echo "</div>";
      echo "<div class='col-xl col-auto'>";
          echo $key->correo;
      echo "</div>";
      echo "<div class='col-xl col-auto'>";
          echo $key->telefono;
      echo "</div>";
    echo "</div>";
  }
  echo "</div>";
?>
