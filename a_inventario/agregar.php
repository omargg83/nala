<?php
	require_once("db_.php");

	$pag=0;
	if(isset($_REQUEST['pag'])){
		$pag=$_REQUEST['pag'];
	}
	$pd = $db->catalogo_lista($pag);
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";

?>

<div class='tabla_css' id='tabla_css'>
  <div class='row titulo-row'>
    <div class='col-12'>
      ASIGNAR PRODUCTO A LA SUCURSAL
    </div>
  </div>
  <div class='row header-row'>
    <div class='col-2'>#</div>
    <div class='col-2'>Tipo</div>
    <div class='col-2'>Codigo</div>
    <div class='col-6'>Nombre</div>
  </div>

    <?php
      foreach($pd as $key){
        echo "<div class='row body-row' draggable='true'>";
          echo "<div class='col-2'>";
            echo "<div class='btn-group'>";

            echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_inventario/db_' des='a_inventario/lista' fun='asignar_sucursal' dix='trabajo' v_idcatalogo='$key->idcatalogo' id='eliminar' tp='¿Desea agregar el Producto seleccionado a la sucursal?'><i class='far fa-trash-alt'></i></button>";

            echo "</div>";
          echo "</div>";

          echo "<div class='col-2'>";
            if($key->tipo==0) echo "Servicio";
            if($key->tipo==3) echo "Producto";
          echo "</div>";

          echo "<div class='col-2'>".$key->codigo."</div>";
          echo "<div class='col-6'>".$key->nombre."</div>";

        echo '</div>';
      }
    ?>

  </div>
</div>


<?php
  $sql="SELECT count(productos_catalogo.idcatalogo) as total
  from productos_catalogo	where productos_catalogo.idtienda='".$_SESSION['idtienda']."'";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();
  $contar=$sth->fetch(PDO::FETCH_OBJ);
  $paginas=ceil($contar->total/$_SESSION['pagina']);
  $pagx=$paginas-1;
  echo "<br>";
  echo "<nav aria-label='Page navigation text-center'>";
    echo "<ul class='pagination'>";
      echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/agregar' dix='trabajo'>Primera</a></li>";
      for($i=0;$i<$paginas;$i++){
        $b=$i+1;
        echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_inventario/agregar' dix='trabajo' v_pag='$i'>$b</a></li>";
      }
      echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/agregar' dix='trabajo' v_pag='$pagx'>Ultima</a></li>";
    echo "</ul>";
  echo "</nav>";

?>
