<?php
	require_once("db_.php");

	$pag=0;
	$texto="";
	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->ventas_buscar($texto);
		$texto=1;
	}
	else{
		if(isset($_REQUEST['pag'])){
			$pag=$_REQUEST['pag'];
		}
		$pd = $db->ventas_lista($pag);
	}

	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
?>
	<div class='tabla_css' id='tabla_css'>
		<div class='row titulo-row'>
			<div class='col-12'>
				LISTA DE VENTAS ABIERTAS
			</div>
		</div>
		<div class='row header-row'>
			<div class='col-2'>#</div>
			<div class='col-2'>Numero</div>
			<div class='col-2'>Fecha</div>
			<div class='col-2'>Cliente</div>
			<div class='col-2'>Total</div>
			<div class='col-2'>Estado</div>
		</div>

			<?php
				foreach($pd as $key){
			?>
					<div class='row body-row' draggable='true'>
						<div class='col-2'>
							<div class="btn-group">
								<?php
									if($db->nivel_captura==1){
										echo "<button class='btn btn-warning btn-sm'  id='edit_persona' is='b-link' id='nueva_venta' des='a_venta/venta' dix='trabajo'  v_idventa='$key->idventa' v_general='1'><i class='fas fa-pencil-alt'></i></button>";
									}
								?>
							</div>
						</div>
						<div class='col-2'><?php echo $key->numero; ?></div>
						<div class='col-2'><?php echo fecha($key->fecha); ?></div>
						<div class='col-2'><?php echo $key->nombre; ?></div>

						<div class='col-2' align="center">$ <?php echo number_format($key->total,2); ?></div>
						<div class='col-2'><?php echo $key->estado; ?></div>

					</div>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
</div>

<?php
	if(strlen($texto)==0){
		$sql="select count(venta.idventa) as total from venta
		left outer join clientes on clientes.idcliente=venta.idcliente
		where venta.idsucursal='".$_SESSION['idsucursal']."' and (venta.estado='Activa' or venta.estado='Editar') order by venta.numero desc";

		$sth = $db->dbh->query($sql);
		$contar=$sth->fetch(PDO::FETCH_OBJ);
		$paginas=ceil($contar->total/$_SESSION['pagina']);
		$pagx=$paginas-1;

		echo $db->paginar($paginas,$pag,$pagx,"a_ventas/lista","trabajo");

	}
?>
