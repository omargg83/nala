<?php
	require_once("db_.php");
  $texto=$_REQUEST['prod_venta'];

	$sql="SELECT
	productos_catalogo.nombre,
	productos_catalogo.codigo,
	productos_catalogo.tipo,
	productos.idproducto,
	productos.activo_producto,
	productos.cantidad,
	productos.precio,
	productos.preciocompra,
	productos.precio_mayoreo,
	productos.precio_distri,
	productos.stockmin,
	productos.idsucursal
	from productos
	LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
	where productos.idsucursal='".$_SESSION['idsucursal']."' and
  (nombre like '%$texto%'or
	descripcion like '%$texto%'or
  codigo like '%$texto%'
  )limit 10";
  $sth = $db->dbh->prepare($sql);
  $sth->execute();
  $res=$sth->fetchAll(PDO::FETCH_OBJ);

	echo "<div class='container'>";
	echo "<div class='tabla_css' id='tabla_css'>";
		echo "<div class='row header-row'>";
			echo "<div class='col-12 col-xl col-auto'><i class='far fa-lightbulb' title='Estatus'></i></div>";
			echo "<div class='col-12 col-xl col-auto'><i class='fas fa-cubes' title='Stock'></i></div>";
			echo "<div class='col-12 col-xl col-auto'><i class='far fa-file-alt' title='Descripción'></i></div>";
			echo "<div class='col-12 col-xl col-auto'><i class='fas fa-dollar-sign' title='Precio' ></i></div>";
			echo "<div class='col-12 col-xl col-auto'><i class='fas fa-dollar-sign' title='Precio Mayoreo' >M</i></div>";
			echo "<div class='col-12 col-xl col-auto'><i class='fas fa-dollar-sign' title='Precio Distribuidor' >D</i></div>";
		echo "</div>";

	  if(count($res)>0){
	    foreach ($res as $key) {
				$sql="select sum(cantidad) as total from bodega where idsucursal='".$_SESSION['idsucursal']."' and idproducto='$key->idproducto'";
				$sth = $db->dbh->prepare($sql);
				$sth->execute();
				$cantidad=$sth->fetch(PDO::FETCH_OBJ);

	      echo "<div class='row body-row' is='b-card' draggable='true'>";
	      echo  "<div class='col-12 col-xl col-auto text-center'>";
		      echo  "<div class='btn-group'>";
					if($cantidad->total>0 and $key->activo_producto==1 ){
						echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-warning btn-sm' title='Producto en existencia' omodal='1'><i class='far fa-thumbs-up'></i></button>";
					}
					else if ($cantidad->total<=0 and $key->activo_producto==1 and $key->tipo<>0){
						echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-danger btn-sm' title='Producto sin stock' omodal='1'><i class='far fa-thumbs-down'></i></button>";
					}
					else if ($key->activo_producto==0){
						echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-secondary  btn-sm' title='Producto o servicio inactivo' omodal='1'><i class='fas fa-ban'></i></i></button>";
					}
					else if ($key->tipo==0){
						echo "<button type='button'  id='0' des='' dix='0' v_idproducto='0' class='btn btn-warning btn-sm' title='Se trata de un servicio' omodal='1'><i class='fas fa-people-carry'></i></button>";
					}

		    echo  "</div>";
	      echo  "</div>";

				echo  "<div class='col-12 col-xl col-auto text-center'>";
					echo $cantidad->total;
	      echo  "</div>";

	      echo  "<div class='col-12 col-xl col-auto text-center'>";
				echo  $key->codigo;
				echo "<br>";
	      echo  $key->nombre;
	      echo  "</div>";

				echo  "<div class='col-12 col-xl col-auto text-center'>";
	        echo 	moneda($key->precio);
	      echo  "</div>";

				echo  "<div class='col-12 col-xl col-auto text-center'>";
					echo 	moneda($key->precio_mayoreo);
				echo  "</div>";

				echo  "<div class='col-12 col-xl col-auto text-center'>";
					echo 	moneda($key->precio_distri);
				echo  "</div>";

	      echo  "</div>";
	    }
	  }
		echo "</div>";
	echo "</div>";
