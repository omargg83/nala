<?php
	require_once("db_.php");
	if(isset($_REQUEST['id'])){
		$id=$_REQUEST['id'];
	}
	echo "<div class='content table-responsive table-full-width'>";
		echo "<table class='table table-sm'>";
		echo "<thead><tr><th>-</th>";
		echo "<th>Aplicación</th><th>Nivel</th><th>Captura</th>";
		echo "</tr></thead><tbody>";

		$sql="select * from usuarios_permiso where idusuario='$id' order by modulo";
		$sth = $db->dbh->prepare($sql);
		$sth->execute();
		$per=$sth->fetchAll(PDO::FETCH_OBJ);

		foreach($per as $permiso){
			echo "<tr>";
			echo "<td>";

			echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_usuarios/db_'
			des='a_usuarios/form_permisos' desid='id'
			fun='borrar_permiso' dix='permisos' v_idpermiso='$permiso->idpermiso' v_idusuario='$id' id='eliminar' tp='¿Desea eliminar el permiso seleccionado?'><i class='far fa-trash-alt'></i></button>";

			echo "</td>";
			echo "<td>";
			echo $permiso->modulo;
			echo "</td>";
			echo "<td><center>";
			echo $permiso->nivel;
			echo "</td>";
			echo "<td><center>";
			echo $permiso->captura;
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	echo "</div>";
?>
