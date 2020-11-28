<?php
	require_once("db_.php");

	$pag=0;
	$texto="";
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->compras_buscar($texto);
	}
	else{
		if(isset($_REQUEST['pag'])){
			$pag=$_REQUEST['pag'];
		}
		$pd = $db->compras_lista($pag);
	}

	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>
	<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-12'>
				LISTA DE COMPRAS
			</div>
		</div>
		<div class='row header-row'>
			<div class='col-2'>#</div>
			<div class='col-2'>Fecha</div>
			<div class='col-2'>Numero</div>
			<div class='col-2'>Nombre</div>
			<div class='col-2'>Proveedor</div>
			<div class='col-2'>Estado</div>
		</div>

			<?php
				foreach($pd as $key){
					echo "<div class='row body-row' draggable='true'>";
						echo "<div class='col-2'>";

							echo "<div class='btn-group'>";

							if($db->nivel_captura==1){
								echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_compras/editar' dix='trabajo' v_idcompra='$key->idcompra'><i class='fas fa-pencil-alt'></i></button>";
								echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_compras/db_' des='a_compras/lista' fun='borrar_compra' dix='trabajo' v_idcompra='$key->idcompra' id='eliminar' tp='¿Desea eliminar la compra seleccionada?'><i class='far fa-trash-alt'></i></button>";

							}
							echo "</div>";

						echo "</div>";

						echo "<div class='col-2'>".fecha($key->fecha)."</div>";
						echo "<div class='col-2'>".$key->numero."</div>";
						echo "<div class='col-2'>".$key->nombre."</div>";
						echo "<div class='col-2'>".$key->idproveedor."</div>";
						echo "<div class='col-2'>".$key->estado."</div>";

					echo "</div>";
				}
			?>
		</div>
	</div>

	<?php
		if(strlen($texto)==0){
			$sql="SELECT count(idsucursal) as total FROM compras where idsucursal='".$_SESSION['idsucursal']."' order by numero desc";
			$sth = $db->dbh->query($sql);
			$contar=$sth->fetch(PDO::FETCH_OBJ);
			$paginas=ceil($contar->total/$_SESSION['pagina']);
			$pagx=$paginas-1;
			echo "<br>";
			echo "<nav aria-label='Page navigation text-center'>";
			  echo "<ul class='pagination'>";
			    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_compras/lista' dix='trabajo'>Primera</a></li>";
					for($i=0;$i<$paginas;$i++){
						$b=$i+1;
						echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_compras/lista' dix='trabajo' v_pag='$i'>$b</a></li>";
					}
			    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_compras/lista' dix='trabajo' v_pag='$pagx'>Ultima</a></li>";
			  echo "</ul>";
			echo "</nav>";
		}
	?>
