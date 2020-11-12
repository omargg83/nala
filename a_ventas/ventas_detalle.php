<?php
	require_once("control_db.php");
	$bdd = new Venta();
	if (isset($_POST['id'])){$id=$_POST['id'];} else{ $id=0;}

	$pd = $bdd->venta($id);

	$idventa=$pd['idventa'];
	$idcliente=$pd['idcliente'];
	$idtienda=$pd['idtienda'];
	$iddescuento=$pd['iddescuento'];
	$lugar=$pd['lugar'];
	$entregar=$pd['entregar'];
	$dentrega=$pd['dentrega'];
	$estado=$pd['estado'];
	$factura=$pd['factura'];
	$fecha=$pd['fecha'];

	$cliente = $bdd->cliente($idcliente);
?>
	<div class='container'>
		<div class='card'>
			<div class='card-header'>Ventas</div>
			<div class='card-body'>
				<div class='row'>
					<div class='col-2'>
						<label>Numero:</label>
						 <input type="text" class="form-control" name="id" id="id" value="<?php echo $idventa ;?>" placeholder="Numero de compra" readonly>
						 <input type="hidden" class="form-control" name="idtienda" id="idtienda" value="<?php echo $idtienda ;?>" placeholder="Tienda" readonly>
						 <input type="hidden" class="form-control" name="modulo" id="modulo" value="ventas"  readonly>
				   </div>
				   <div class='col-2'>
						<label >Fecha:</label>
						<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $fecha ;?>" placeholder="Fecha de entrega" readonly>
				   </div>
				   <div class='col-2'>
						<label >Cliente:</label>
						<input type="text" class="form-control" name="cliente" id="cliente" value="<?php echo $cliente['razon_social_prove'] ;?>" placeholder="Cliente" readonly>
				   </div>
			   </div>
			</div>
			<div class='card-body'>
			   <?php
					if($estado=="Activa"){
				?>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
					 <i class="fas fa-plus"></i> Productos
					</button>

				<?php
					}
				?>
			</div>

			<div class='card-body' id='pedido'>

			</div>
		</div>
	 </div>

	  <!-- Modal -->
            <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Buscar productos</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
                  </div>
                  <div class="modal-body">
						<div class='row'>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="q_prod" placeholder="Buscar productos" onkeyup="buscar_prod()" autocomplete=off>
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-info btn-sm" onclick="buscar_prod()"><i class="fab fa-searchengin"></i> Buscar</button>
							</div>
						</div>
                    <div id="loader" style="position: absolute; text-align: center; top: 55px;  width: 100%;display:none;"></div><!-- Carga gif animado -->
                     <div class="outer_div" style='height:350px; overflow:auto;'>

					</div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
