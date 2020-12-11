<?php
	require_once("db_.php");

  if(isset($_REQUEST['idcliente'])){
    $idcliente=$_REQUEST['idcliente'];
    $sql="select * from clientes where idtienda=".$_SESSION['idtienda']." and idcliente=$idcliente ";
    $sth = $db->dbh->prepare($sql);
    $sth->execute();
    $cliente=$sth->fetch(PDO::FETCH_OBJ);
    $n_cliente=$cliente->nombre;
    $idcliente=$cliente->idcliente;
  }
	if(isset($_REQUEST['idventa'])){
		$idventa=$_REQUEST['idventa'];
		$pd = $db->venta($idventa);
		$estado_compra=$pd->estado;
	}
?>

<input type="hidden" name="idcliente" id="idcliente" value="<?php echo $idcliente; ?>" readonly>
<div class='col-xl col-auto'>
  <div class="input-group">
    <input type="text" class="form-control form-control-sm" name="n_cliente" id='n_cliente' placeholder='Cliente' aria-label="buscar producto" aria-describedby="basic-addon2" value="<?php echo $n_cliente; ?>" readonly>

  </div>
</div>
