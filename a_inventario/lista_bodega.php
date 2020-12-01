<?php
	require_once("db_.php");

  $idproducto=$_REQUEST['idproducto'];
  $pag=0;
  if(isset($_REQUEST['pag'])){
    $pag=$_REQUEST['pag'];
  }
  $row=$db->productos_inventario($idproducto, $pag);

  echo "<div class='card'>";
  echo "<div class='card-body'>";

    echo "<div class='tabla_css' id='tabla_css'>";
  		echo "<div class='row header-row'>";
  			echo "<div class='col-2'>Fecha</div>";
  			echo "<div class='col-2'>Tipo</div>";
  			echo "<div class='col-4'>Descripción</div>";
  			echo "<div class='col-2'>Cantidad</div>";
  			echo "<div class='col-2'>Existencia</div>";
  		echo "</div>";

      $total=0;
      foreach($row as $key){
        echo "<div class='row body-row' draggable='true'>";
          echo "<div class='col-2'>";
            echo fecha($key->fecha);
          echo "</div>";
          echo "<div class='col-2'>";
            if($key->cantidad>0 and strlen($key->idcompra)==0 and strlen($key->idpadre)==0){
              echo "Ingreso";
            }
            if($key->cantidad>0 and strlen($key->idcompra)>0){
              echo "Compra";
            }
            if($key->cantidad<0 and strlen($key->idventa)>0){
              echo "Venta";
            }
            if($key->cantidad<0 and strlen($key->idtraspaso)>0){
              echo "Traspaso";
            }
						if($key->cantidad>0 and strlen($key->idpadre)>0){
              echo "Ingreso x traspaso";
            }
						$usuario=$db->usuario($key->idpersona);
						echo "<br>";
						echo $usuario->nombre;
          echo "</div>";
          echo "<div class='col-4'>";

            if(strlen($key->idtraspaso)>0){
              $sql="select * from traspasos where idtraspaso=$key->idtraspaso";
              $sth = $db->dbh->query($sql);
              $traspaso=$sth->fetch(PDO::FETCH_OBJ);
              echo "#:".$traspaso->numero;
              echo "<br>".$traspaso->nombre;
            }
            if(strlen($key->idcompra)>0){
              $sql="select * from compras where idcompra=$key->idcompra";
              $sth = $db->dbh->query($sql);
              $compra=$sth->fetch(PDO::FETCH_OBJ);
              echo "#:".$compra->numero;
              echo "<br>".$compra->nombre;
            }
            if(strlen($key->idventa)>0){
              $sql="select * from venta where idventa=$key->idventa";
              $sth = $db->dbh->query($sql);
              $venta=$sth->fetch(PDO::FETCH_OBJ);
              echo "#:".$venta->numero;
            }
						if(strlen($key->idpadre)>0){
							$sql="select * from bodega where idbodega='$key->idpadre'";
							$sth = $db->dbh->query($sql);
							$origen=$sth->fetch(PDO::FETCH_OBJ);

							$sql="select * from traspasos where idtraspaso=$origen->idtraspaso";
							$sth = $db->dbh->query($sql);
              $traspaso=$sth->fetch(PDO::FETCH_OBJ);
							echo "#:".$traspaso->numero;
							echo "<br>".$traspaso->nombre;
						}
          echo "</div>";

          echo "<div class='col-2 text-center'>";
            echo $key->cantidad;
          echo "</div>";

          echo "<div class='col-2 text-center'>";
            echo $key->existencia;
          echo "</div>";

        echo "</div>";
      }
    echo "</div>";
  echo "</div>";
echo "</div>";


    $sql="select count(bodega.idbodega) as total from bodega where idproducto=$idproducto and idsucursal='".$_SESSION['idsucursal']."' order by idbodega desc";
		$sth = $db->dbh->query($sql);
		$contar=$sth->fetch(PDO::FETCH_OBJ);
		$paginas=ceil($contar->total/$_SESSION['pagina']);
		$pagx=$paginas-1;
		echo "<br>";
		echo "<nav aria-label='Page navigation text-center'>";
		  echo "<ul class='pagination'>";
		    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista_bodega' v_idproducto='$idproducto' dix='registro_bodega'>Primera</a></li>";
				for($i=0;$i<$paginas;$i++){
					$b=$i+1;
					echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista_bodega' dix='registro_bodega' v_pag='$i' v_idproducto='$idproducto'>$b</a></li>";
				}
		    echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_inventario/lista_bodega' dix='registro_bodega' v_pag='$pagx' v_idproducto='$idproducto'>Ultima</a></li>";
		  echo "</ul>";
		echo "</nav>";
  echo "</div>";
?>
