<?php
	require_once("db_.php");

	$pag=0;
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->producto_buscar($texto);
	}
	else{
		if(isset($_REQUEST['pag'])){
			$pag=$_REQUEST['pag'];
		}
		$pd = $db->productos_lista($pag);
	}
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>

	<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-12'>
				INVENTARIO DE PRODUCTOS
			</div>
		</div>
		<div class='row header-row'>
			<div class='col-2'>#</div>
			<div class='col-2'>Tipo</div>
			<div class='col-3'>Nombre</div>
			<div class='col-2'>Estatus</div>
			<div class='col-1'>Existencia</div>
			<div class='col-2'>Precio de venta</div>
		</div>

			<?php
				foreach($pd as $key){
					echo "<div class='row body-row' draggable='true'>";
						echo "<div class='col-2'>";
							echo "<div class='btn-group'>";

							echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_inventario/editar' dix='trabajo' v_idproducto='$key->idproducto'><i class='fas fa-pencil-alt'></i></button>";

							echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_inventario/db_' des='a_inventario/lista' fun='borrar_producto' dix='trabajo' v_idproducto='$key->idproducto' id='eliminar' tp='¿Desea eliminar el Producto seleccionado?'><i class='far fa-trash-alt'></i></button>";
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

						echo "<div class='col-2'>";
							if($key->tipo==0) echo "Servicio";
							if($key->tipo==3) echo "Volúmen";
						echo "</div>";

						echo "<div class='col-2'>".$key->nombre."</div>";

						echo "<div class='col-3 text-center'>";
						if($cantidad->total>0 and $key->activo_producto==1 ){
							echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-warning btn-sm' title='Producto en existencia' omodal='1'><i class='far fa-thumbs-up'></i></button>";
						}
						else if ($cantidad->total<=0 and $key->activo_producto==1){
							echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-danger btn-sm' title='Producto sin stock' omodal='1'><i class='far fa-thumbs-down'></i></button>";
						}
						else if ($key->activo_producto==0){
							echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-secondary btn-sm' title='Producto inactivo' omodal='1'><i class='fas fa-ban'></i></button>";
						}
						echo "</div>";

						echo "<div class='col-1 text-center'>";
							echo $exist;
						echo "</div>";

						echo "<div class='col-2 text-right' >".moneda($key->precio)."</div>";
					echo '</div>';
				}
			?>
		</div>
	</div>

<?php
	$sql="SELECT count(productos.idproducto) as total
	from productos LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo	where productos.idsucursal='".$_SESSION['idsucursal']."' and productos_catalogo.tipo<>0";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$contar=$sth->fetch(PDO::FETCH_OBJ);
	$paginas=ceil($contar->total/$_SESSION['pagina']);
	$pagx=$paginas-1;
	echo "<br>";
	echo "<nav aria-label='Page navigation text-center'>";
	  echo "<ul class='pagination'>";
	    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista' dix='trabajo'>Primera</a></li>";
			for($i=0;$i<$paginas;$i++){
				$b=$i+1;
				echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista' dix='trabajo' v_pag='$i'>$b</a></li>";
			}
	    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista' dix='trabajo' v_pag='$pagx'>Ultima</a></li>";
	  echo "</ul>";
	echo "</nav>";

?>
