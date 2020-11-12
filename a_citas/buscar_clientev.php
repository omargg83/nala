<?php
	require_once("db_.php");
  $texto=$_REQUEST['texto'];
  $idcliente=$_REQUEST['idcliente'];
  $idcita=$_REQUEST['idcita'];

  $sql="SELECT * from clientes where nombre like '%$texto%' limit 100";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();
  echo "<table class='table table-sm'>";
  echo "<tr><th>-</th><th>Prof.</th><th>Nombre </th><th>Correo</th><th>Teléfono</th></tr>";
  foreach($sth->fetchAll(PDO::FETCH_OBJ) as $key){
    echo "<tr>";
      echo "<td>";

        echo "<button type='button' is='b-link' db='a_citas/db_' fun='agrega_cliente' des='a_citas/editar' desid='idcita' dix='trabajo' v_idcliente='$key->idcliente' v_idcita='$idcita' cmodal='2' class='btn btn-warning btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";

      echo "</td>";

      echo "<td>";
          echo $key->nombre;
      echo "</td>";
      echo "<td>";
          echo $key->correo;
      echo "</td>";
      echo "<td>";
          echo $key->telefono;
      echo "</td>";
    echo "</tr>";
  }
  echo "</table>";
?>
