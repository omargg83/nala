<?php
	require_once("db_.php");

	$pag=0;
	$buscar="";
	if(isset($_REQUEST['pag'])){
		$pag=$_REQUEST['pag'];
	}

	if(isset($_REQUEST['buscar'])){
		$buscar=$_REQUEST['buscar'];
	}

	$pd = $db->catalogo_lista($pag, $buscar);

	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
		echo "<div class='row'>";
			echo "<div class='col-xl col-auto'>";
				echo "<label>Buscar</label>";
				echo "<form is='b-submit' id='form_bagrega' des='a_inventario/agregar' dix='trabajo' >";
					echo "<div clas='row'>";
							echo "<div class='input-group mb-3'>";
							echo "<input type='text' class='form-control form-control-sm' name='buscar' id='buscar' placeholder='buscar producto' aria-label='buscar producto' aria-describedby='basic-addon2' value='$buscar'>";
							echo "<div class='input-group-append'>";
								echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fas fa-search'></i>Buscar</button>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</form>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo "<br>";
	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
?>

	<div class='tabla_css' id='tabla_css'>
	  <div class='row titulo-row'>
	    <div class='col-xl col-auto'>
	      ASIGNAR PRODUCTO A LA SUCURSAL
	    </div>
	  </div>
	  <div class='row header-row'>
	    <div class='col-xl col-auto'>#</div>
	    <div class='col-xl col-auto'>Tipo</div>
	    <div class='col-xl col-auto'>Codigo</div>
	    <div class='col-xl col-auto'>Nombre</div>
	  </div>

	    <?php
	      foreach($pd as $key){
	        echo "<div class='row body-row' draggable='true'>";
	          echo "<div class='col-xl col-auto'>";
	            echo "<div class='btn-group'>";


								if($db->nivel_captura==1){
										echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_inventario/db_' des='a_inventario/lista' fun='asignar_sucursal' dix='trabajo' v_idcatalogo='$key->idcatalogo' id='asignar' tp='¿Desea agregar el Producto seleccionado a la sucursal?'><i class='fas fa-cloud-download-alt'></i></button>";
								}

	            echo "</div>";
	          echo "</div>";

	          echo "<div class='col-xl col-auto text-center'>";
	            if($key->tipo==0) echo "Servicio";
	            if($key->tipo==3) echo "Producto";
	          echo "</div>";

	          echo "<div class='col-xl col-auto text-center'>".$key->codigo."</div>";
	          echo "<div class='col-xl col-auto text-center'>".$key->nombre."</div>";

	        echo '</div>';
	      }
	    ?>

	  </div>
	</div>
<br>
<div class="container">
<?php
	if(strlen($buscar)==0){
	  $sql="SELECT count(productos_catalogo.idcatalogo) as total
	  from productos_catalogo	where productos_catalogo.idtienda='".$_SESSION['idtienda']."'";
	  $sth = $db->dbh->prepare($sql);
	  $sth->execute();
	  $contar=$sth->fetch(PDO::FETCH_OBJ);
	  $paginas=ceil($contar->total/$_SESSION['pagina']);
	  $pagx=$paginas-1;

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
	}
?>
</div>
