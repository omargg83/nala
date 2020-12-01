<?php
	require_once("db_.php");

	$pag=0;
	$texto="";
	if(isset($_REQUEST['pag'])){
		$pag=$_REQUEST['pag'];
	}
	$pd = $db->servicios_lista($pag);
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>

	<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-xl col-auto'>
				INVENTARIO DE SERVICIOS
			</div>
		</div>
		<div class='row header-row'>
			<div class='col-xl col-auto'>#</div>
			<div class='col-xl col-auto'>Tipo</div>
			<div class='col-xl col-auto'>Nombre</div>
			<div class='col-xl col-auto'>Estatus</div>
			<div class='col-xl col-auto'>Existencia</div>
			<div class='col-xl col-auto'>Precio de servicio</div>
		</div>

			<?php
				foreach($pd as $key){
					echo "<div class='row body-row' draggable='true'>";
						echo "<div class='col-xl col-auto'>";
							echo "<div class='btn-group'>";

							echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_inventario/editar' dix='trabajo' v_idproducto='$key->idproducto'><i class='fas fa-pencil-alt'></i></button>";


							echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_inventario/db_' des='a_inventario/lista_servicios' fun='borrar_producto' dix='trabajo' v_idproducto='$key->idproducto' id='eliminar' tp='¿Desea eliminar el Producto seleccionado?'><i class='far fa-trash-alt'></i></button>";
								////
								if($key->tipo==3){
									$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$key->idproducto'";
									$sth = $db->dbh->prepare($sql);
									$sth->execute();
									$cantidad=$sth->fetch(PDO::FETCH_OBJ);
									$exist=$cantidad->total;
								}
								else{
									$exist=$key->cantidad;
								}
							//////
							echo "</div>";
						echo "</div>";

						echo "<div class='col-xl col-auto'>";
							if($key->tipo==0) echo "Servicio";
							if($key->tipo==3) echo "Volúmen";
						echo "</div>";

						echo "<div class='col-xl col-auto'>".$key->nombre."</div>";

						echo "<div class='col-xl col-auto text-center'>";
						if($key->activo_producto==1){
							echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-warning btn-sm' title='Servicio activo' omodal='1'><i class='fas fa-people-carry'></i></button>";
						}
						else {
							echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-secondary btn-sm' title='Servicio inactivo' omodal='1'><i class='fas fa-ban'></i></button>";
						}
						echo "</div>";

						echo "<div class='col-xl col-auto text-center'>";
							echo $exist;
						echo "</div>";

						echo "<div class='col-xl col-auto text-right' >".moneda($key->precio)."</div>";
					echo '</div>';
				}
			?>
		</div>
	</div>

	<?php
		if(strlen($texto)==0){
			$sql="SELECT count(productos.idproducto) as total
			from productos LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo	where productos.idsucursal='".$_SESSION['idsucursal']."' and productos_catalogo.tipo=0";
			$sth = $db->dbh->query($sql);
			$contar=$sth->fetch(PDO::FETCH_OBJ);
			$paginas=ceil($contar->total/$_SESSION['pagina']);
			$pagx=$paginas-1;
			echo "<br>";
			echo "<nav aria-label='Page navigation text-center'>";
			  echo "<ul class='pagination'>";
			    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista_servicios' dix='trabajo'>Primera</a></li>";
					for($i=0;$i<$paginas;$i++){
						$b=$i+1;
						echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista_servicios' dix='trabajo' v_pag='$i'>$b</a></li>";
					}
			    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista_servicios' dix='trabajo' v_pag='$pagx'>Ultima</a></li>";
			  echo "</ul>";
			echo "</nav>";
		}
	?>
