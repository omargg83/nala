<?php
	require_once("db_.php");
  $texto=$_REQUEST['texto_cita'];
  $idventa=$_REQUEST['idventa'];


  $sql="SELECT * from citas left outer join clientes on clientes.idcliente=citas.idcliente
  where citas.estatus='PENDIENTE' and (clientes.nombre like '%$texto%' or clientes.apellidop like '%$texto%' or clientes.apellidom like '%$texto%' or citas.asunto like '%$texto%')";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();
  echo "<table class='table table-sm'>";
  echo "<tr><th>-</th><th>#</th><th>Nombre </th><th>Fecha</th><th>Estatus</th><th>Precio</th><th>Articulos</th></tr>";
  foreach($sth->fetchAll(PDO::FETCH_OBJ) as $key){
    echo "<tr>";
      echo "<td>";
        echo "<div class='btn-group'>";
        echo "<button type='button' onclick='sel_cita(".$key->idcitas.",$idventa)' class='btn btn-warning btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
        echo "</div>";
      echo "</td>";
      echo "<td>";
        echo $key->idcitas;
      echo "</td>";
      echo "<td>";
          echo $key->profesion." ".$key->nombre." ".$key->apellidop." ".$key->apellidom;
      echo "</td>";
      echo "<td>";
          echo fecha($key->fecha,2);
      echo "</td>";
      echo "<td>";
          echo $key->estatus;
      echo "</td>";
      echo "<td>";
          echo moneda($key->precio);
      echo "</td>";
      echo "<td>";
          echo moneda($key->total);
      echo "</td>";
    echo "</tr>";
  }
  echo "</table>";
?>
