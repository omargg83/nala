<?php
	require_once("db_.php");

	$pag=0;
	$texto="";
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->provedores_buscar($texto);
	}
	else{
		if(isset($_REQUEST['pag'])){
			$pag=$_REQUEST['pag'];
		}
		$pd = $db->provedores_lista($pag);
	}
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>


<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12 col-xl col-auto'>
			LISTA DE PROVEEDORES
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-12 col-xl col-auto'>#</div>
		<div class='col-12 col-xl col-auto'>NOMBRE</div>
		<div class='col-12 col-xl col-auto'>EMAIL</div>
		<div class='col-12 col-xl col-auto'>TELEFONO</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-12 col-xl col-auto text-center'>";
						echo "<div class='btn-group'>";

						if($db->nivel_captura==1){
							echo "<button type='button' class='btn btn-warning btn-sm' id='edit_persona' is='b-link' title='Editar' des='a_proveedores/editar' dix='trabajo' v_idproveedor='$key->idproveedor'><i class='fas fa-pencil-alt'></i></button>";
						}
						echo "</div>";
					echo "</div>";

					echo "<div class='col-12 col-xl col-auto text-center'>".$key->nombre."</div>";
					echo "<div class='col-12 col-xl col-auto text-center'>".$key->emailp."</div>";
					echo "<div class='col-12 col-xl col-auto text-center'>".$key->telp."</div>";
				echo "</div>";
			}
		?>
	</div>
</div>

<?php
	if(strlen($texto)==0){
		$sql="SELECT count(idproveedor) as total FROM proveedores where idtienda='".$_SESSION['idtienda']."'";
		$sth = $db->dbh->query($sql);
		$contar=$sth->fetch(PDO::FETCH_OBJ);
		$paginas=ceil($contar->total/$_SESSION['pagina']);
		$pagx=$paginas-1;
		echo "<br>";
		echo "<nav aria-label='Page navigation text-center'>";
			echo "<ul class='pagination'>";
				echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_proveedores/lista' dix='trabajo'>Primera</a></li>";
				for($i=0;$i<$paginas;$i++){
					$b=$i+1;
					echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_proveedores/lista' dix='trabajo' v_pag='$i'>$b</a></li>";
				}
				echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_proveedores/lista' dix='trabajo' v_pag='$pagx'>Ultima</a></li>";
			echo "</ul>";
		echo "</nav>";
	}
?>
