<?php
	require_once("db_.php");

	$pag=0;
	$texto="";
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->usuario_buscar($texto);
	}
	else{
		if(isset($_REQUEST['pag'])){
			$pag=$_REQUEST['pag'];
		}
		$pd = $db->usuario_lista($pag);
	}
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>
<div class='tabla_css' id='tabla_css'>
	<div class='row titulo-row'>
		<div class='col-12'>
			LISTA DE USUARIOS
		</div>
	</div>
	<div class='row header-row'>
		<div class='col-2'>#</div>
		<div class='col-4'>NOMBRE</div>
		<div class='col-4'>CORREO</div>
		<div class='col-2'>ACTIVO</div>
	</div>

		<?php
			foreach($pd as $key){
				echo "<div class='row body-row' draggable='true'>";
					echo "<div class='col-2'>";
						if($db->nivel_captura==1){
							echo "<button class='btn btn-warning btn-sm' is='b-link' des='a_usuarios/editar' dix='trabajo' v_id='$key->idusuario' id='edit_persona'><i class='fas fa-pencil-alt'></i></button>";
							
						}
						echo "</div>";
					echo "<div class='col-4'>".$key->nombre."</div>";
					echo "<div class='col-4'>".$key->correo."</div>";
					echo "<div class='col-2'>";
						if ($key->activo==0) { echo "Inactivo"; }
						if ($key->activo==1) { echo "Activo"; }
					echo "</div>";
				echo "</div>";
			}
		?>
	</div>
</div>


<?php
	if(strlen($texto)==0){
		$sql="SELECT count(usuarios.idusuario) as total FROM usuarios
		LEFT OUTER JOIN tienda ON tienda.idtienda = usuarios.idtienda
		where tienda.idtienda='".$_SESSION['idtienda']."'";
		$sth = $db->dbh->query($sql);
		$contar=$sth->fetch(PDO::FETCH_OBJ);
		$paginas=ceil($contar->total/$_SESSION['pagina']);
		$pagx=$paginas-1;
		echo "<br>";
		echo "<nav aria-label='Page navigation text-center'>";
			echo "<ul class='pagination'>";
				echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_usuarios/lista' dix='trabajo'>Primera</a></li>";
				for($i=0;$i<$paginas;$i++){
					$b=$i+1;
					echo "<li class='page-item"; if($pag==$i){ echo " active";} echo "'><a class='page-link' is='b-link' title='Editar' des='a_usuarios/lista' dix='trabajo' v_pag='$i'>$b</a></li>";
				}
				echo "<li class='page-item'><a class='page-link' is='b-link' title='Editar' des='a_usuarios/lista' dix='trabajo' v_pag='$pagx'>Ultima</a></li>";
			echo "</ul>";
		echo "</nav>";
	}
?>
