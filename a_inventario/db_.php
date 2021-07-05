<?php
require_once("../control_db.php");

if($_SESSION['des']==1 and strlen($function)==0)
{
	echo "<div class='alert alert-primary' role='alert' style='font-size:10px'>";
	$arrayx=explode('/', $_SERVER['SCRIPT_NAME']);
	echo print_r($arrayx);
	echo "<br>";
	echo print_r($_REQUEST);
	echo "</div>";
}

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Productos extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('INVENTARIO', $this->derecho)) {
			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='INVENTARIO'";
			$stmt= $this->dbh->query($sql);

			$row =$stmt->fetchObject();
			$this->nivel_personal=$row->nivel;
			$this->nivel_captura=$row->captura;
		}
		else{
			include "../error.php";
			die();
		}
		$this->doc="a_archivos/productos/";
	}
	public function usuario($id){
		$sql="select * from usuarios where idusuario='$id'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_OBJ);
	}
	public function producto_buscar($texto){
		$sql="SELECT
		productos_catalogo.nombre,
		productos_catalogo.codigo,
		productos_catalogo.tipo,
		productos_catalogo.idcatalogo,
		productos.idproducto,
		productos.idcatalogo,
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
		)limit 50";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
  }
	public function productos_lista($pagina){
		try{
			$pagina=$pagina*$_SESSION['pagina'];
			$sql="SELECT
			productos_catalogo.nombre,
			productos_catalogo.codigo,
			productos_catalogo.tipo,
			productos_catalogo.idcatalogo,
			productos.*
			from productos
			LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
			where productos.idsucursal='".$_SESSION['idsucursal']."'and productos_catalogo.tipo=3 limit $pagina,".$_SESSION['pagina']."";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function duplicados_lista(){
		try{

			$sql="SELECT count(productos_catalogo.idcatalogo) as duplicados,
			productos_catalogo.nombre,
			productos_catalogo.codigo,
			productos_catalogo.tipo,
			productos_catalogo.idcatalogo,
			productos.*
			from productos
			LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
			where productos.idsucursal='".$_SESSION['idsucursal']."' group by productos_catalogo.idcatalogo order by productos_catalogo.idcatalogo";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}


	public function catalogo_lista($pagina, $texto){
		try{
			$texto=clean_var($texto);
			$pagina=$pagina*$_SESSION['pagina'];
			$sql="SELECT * from productos_catalogo where activo_catalogo=1 and (productos_catalogo.nombre like '%$texto%' or productos_catalogo.codigo like '%$texto%') order by nombre asc, idcatalogo asc limit $pagina,".$_SESSION['pagina']."";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function barras(){
		$idcatalogo=$_REQUEST['idcatalogo'];
		$codigo="9".str_pad($idcatalogo, 8, "0", STR_PAD_LEFT);
		$arreglo =array();
		$arreglo = array('codigo'=>$codigo);
		return $this->update('productos_catalogo',array('idcatalogo'=>$idcatalogo), $arreglo);
	}

	public function servicios_lista($pagina){
		try{
			$pagina=$pagina*$_SESSION['pagina'];

			$sql="SELECT
			productos_catalogo.nombre,
			productos_catalogo.codigo,
			productos_catalogo.tipo,
			productos.*
			from productos
			LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
			where productos.idsucursal='".$_SESSION['idsucursal']."'and productos_catalogo.tipo=0 limit $pagina,".$_SESSION['pagina']."";

			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function borrar_producto(){
		if (isset($_REQUEST['idproducto'])){ $idproducto=$_REQUEST['idproducto']; }
		return $this->borrar('productos',"idproducto",$idproducto);
	}

	public function producto_editar($id){
		try{
			$sql="SELECT * from productos LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo where productos.idproducto=:id ";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$id);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_producto(){
		try{
			$idproducto=clean_var($_REQUEST['idproducto']);

			$sql="SELECT * from productos where productos.idproducto=:id ";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$idproducto);
			$sth->execute();
			$prod=$sth->fetch(PDO::FETCH_OBJ);

			$idcatalogo=$prod->idcatalogo;

			$arreglo =array();
			$tipo="";
			$imei="";

			if (isset($_REQUEST['activo_producto'])){
				$arreglo += array('activo_producto'=>clean_var($_REQUEST['activo_producto']));
			}

			if (isset($_REQUEST['precio']) and strlen($_REQUEST['precio'])>0){
				$arreglo += array('precio'=>$_REQUEST['precio']);
			}
			else{
				$arreglo += array('precio'=>0);
			}

			if (isset($_REQUEST['stockmin']) and strlen($_REQUEST['stockmin'])>0){
				$arreglo += array('stockmin'=>$_REQUEST['stockmin']);
			}
			else{
				$arreglo += array('stockmin'=>0);
			}

			if (isset($_REQUEST['esquema']) and strlen($_REQUEST['precio'])>0){
				$arreglo += array('esquema'=>$_REQUEST['esquema']);
			}
			if (isset($_REQUEST['cantidad_mayoreo']) and strlen($_REQUEST['cantidad_mayoreo'])>0){
				$arreglo += array('cantidad_mayoreo'=>$_REQUEST['cantidad_mayoreo']);
			}
			else{
				$arreglo += array('cantidad_mayoreo'=>0);
			}

			if (isset($_REQUEST['precio_mayoreo']) and strlen($_REQUEST['precio_mayoreo'])>0){
				$arreglo += array('precio_mayoreo'=>$_REQUEST['precio_mayoreo']);
			}
			else{
				$arreglo += array('precio_mayoreo'=>0);
			}

			if (isset($_REQUEST['monto_mayor']) and strlen($_REQUEST['monto_mayor'])>0){
				$arreglo += array('monto_mayor'=>$_REQUEST['monto_mayor']);
			}
			else{
				$arreglo += array('monto_mayor'=>0);
			}

			if (isset($_REQUEST['monto_distribuidor']) and strlen($_REQUEST['monto_distribuidor'])>0){
				$arreglo += array('monto_distribuidor'=>$_REQUEST['monto_distribuidor']);
			}
			else{
				$arreglo += array('monto_distribuidor'=>0);
			}

			if (isset($_REQUEST['monto_super']) and strlen($_REQUEST['monto_super'])>0){
				$arreglo += array('monto_super'=>$_REQUEST['monto_super']);
			}
			else{
				$arreglo += array('monto_super'=>0);
			}

			if (isset($_REQUEST['precio_distri']) and strlen($_REQUEST['precio_distri'])>0){
				$arreglo += array('precio_distri'=>$_REQUEST['precio_distri']);
			}
			else{
				$arreglo += array('precio_distri'=>0);
			}

			if (isset($_REQUEST['precio_super']) and strlen($_REQUEST['precio_super'])>0){
				$arreglo += array('precio_super'=>$_REQUEST['precio_super']);
			}
			else{
				$arreglo += array('precio_super'=>0);
			}

			if (isset($_REQUEST['mayoreo_cantidad']) and strlen($_REQUEST['mayoreo_cantidad'])>0){
				$arreglo += array('mayoreo_cantidad'=>$_REQUEST['mayoreo_cantidad']);
			}
			else{
				$arreglo += array('mayoreo_cantidad'=>0);
			}

			if (isset($_REQUEST['distri_cantidad']) and strlen($_REQUEST['distri_cantidad'])>0){
				$arreglo += array('distri_cantidad'=>$_REQUEST['distri_cantidad']);
			}
			else{
				$arreglo += array('distri_cantidad'=>0);
			}

			if (isset($_REQUEST['super_cantidad']) and strlen($_REQUEST['super_cantidad'])>0){
				$arreglo += array('super_cantidad'=>$_REQUEST['super_cantidad']);
			}
			else{
				$arreglo += array('super_cantidad'=>0);
			}

			if (isset($_REQUEST['preciocompra']) and strlen($_REQUEST['preciocompra'])>0  ){
				$arreglo += array('preciocompra'=>$_REQUEST['preciocompra']);
			}
			else{
				$arreglo += array('preciocompra'=>0);
			}

			$x=$this->update('productos',array('idcatalogo'=>$idcatalogo), $arreglo);

			$arreglo =array();
			$arreglo+=array('id'=>$idproducto);
			$arreglo+=array('error'=>0);
			return json_encode($arreglo);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function genera_barras(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$codigo="9".str_pad($id, 8, "0", STR_PAD_LEFT);
			$arreglo =array();

			$arreglo = array('codigo'=>$codigo);
			$arreglo+=array('fechamod'=>date("Y-m-d H:i:s"));
			$x=$this->update('productos',array('id'=>$id), $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function existencia_agrega(){
		try{

			if($_REQUEST['cantidad']<1){
				$arreglo =array();
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Error de cantidad, favor de verificar");
				return json_encode($arreglo);
			}

			$id=$_REQUEST['id'];
			$idproducto=$_REQUEST['idproducto'];
			$arreglo =array();
			$arreglo = array('idproducto'=>$idproducto);

			if (isset($_REQUEST['cantidad'])){
				$arreglo += array('cantidad'=>$_REQUEST['cantidad']);
			}
			if (isset($_REQUEST['precio'])){
				$arreglo += array('c_precio'=>$_REQUEST['precio']);
			}
			if (isset($_REQUEST['observaciones'])){
				$arreglo += array('observaciones'=>$_REQUEST['observaciones']);
			}
			if (isset($_REQUEST['idcompra'])){
				if($_REQUEST['idcompra']>0){
					$arreglo += array('idcompra'=>$_REQUEST['idcompra']);
				}
				else{
					$arreglo += array('idcompra'=>null);
				}
			}

			$x="";
			if($id==0){
				$arreglo+=array('fecha'=>date("Y-m-d H:i:s"));
				$arreglo+=array('fechaalta'=>date("Y-m-d H:i:s"));
				$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
				$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
				$x=$this->insert('bodega', $arreglo);
			}
			else{
				$arreglo+=array('fechamod'=>date("Y-m-d H:i:s"));
				$x=$this->update('bodega',array('id'=>$id), $arreglo);
			}
			$ped=json_decode($x);
			if($ped->error==0){

				parent::recalcular($idproducto);

				$arreglo =array();
				$arreglo+=array('id'=>$idproducto);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>0);
				$arreglo+=array('param1'=>"");
				$arreglo+=array('param2'=>"");
				$arreglo+=array('param3'=>"");
				return json_encode($arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function existencia_quita(){
		try{

			if($_REQUEST['cantidad']<1){
				$arreglo =array();
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Error de cantidad, favor de verificar");
				return json_encode($arreglo);
			}

			$id=$_REQUEST['id'];
			$idproducto=$_REQUEST['idproducto'];
			$arreglo =array();
			$arreglo = array('idproducto'=>$idproducto);

			if (isset($_REQUEST['cantidad'])){
				$cantidad=$_REQUEST['cantidad']*-1;
				$arreglo += array('cantidad'=>$cantidad);
			}

			if (isset($_REQUEST['observaciones'])){
				$arreglo += array('observaciones'=>$_REQUEST['observaciones']);
			}


			$x="";
			if($id==0){
				$arreglo+=array('fecha'=>date("Y-m-d H:i:s"));
				$arreglo+=array('fechaalta'=>date("Y-m-d H:i:s"));
				$arreglo+=array('idpersona'=>$_SESSION['idusuario']);
				$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
				$x=$this->insert('bodega', $arreglo);
			}
			else{
				$arreglo+=array('fechamod'=>date("Y-m-d H:i:s"));
				$x=$this->update('bodega',array('id'=>$id), $arreglo);
			}
			$ped=json_decode($x);
			if($ped->error==0){

				parent::recalcular($idproducto);

				$arreglo =array();
				$arreglo+=array('id'=>$idproducto);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>0);
				$arreglo+=array('param1'=>"");
				$arreglo+=array('param2'=>"");
				$arreglo+=array('param3'=>"");
				return json_encode($arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function productos_inventario($id,$pagina){
		try{
			$pagina=$pagina*$_SESSION['pagina'];
			$sql="select * from bodega where idproducto=$id and idsucursal='".$_SESSION['idsucursal']."' order by fecha desc limit $pagina,".$_SESSION['pagina']."";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function borrar_ingreso(){

		$idbodega=$_REQUEST['idbodega'];

		$sql="SELECT * from bodega where idbodega=:id";
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(":id",$idbodega);
		$sth->execute();
		$bodega=$sth->fetch(PDO::FETCH_OBJ);

		$x=$this->borrar('bodega',"idbodega",$bodega->idbodega);

		$ped=json_decode($x);
		if($ped->error==0){
			parent::recalcular($bodega->idproducto, "FECHA" ,$bodega->fecha);
		}

		$arreglo =array();
		$arreglo+=array('id'=>$bodega->idproducto);
		$arreglo+=array('error'=>0);
		$arreglo+=array('terror'=>0);
		return json_encode($arreglo);
	}
	public function sucursal(){
		try{
			$sql="SELECT * FROM sucursal where idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function categoria(){
		try{
			$sql="SELECT * FROM categorias where idtienda='".$_SESSION['idtienda']."'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function compras_lista(){
		try{
			$sql="SELECT * FROM compras where idsucursal='".$_SESSION['idsucursal']."' order by numero desc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function excel(){
		$direccion="tmp/Inventario_por_sucursal.xlsx";

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1',"Inventario por sucursal");
		$sheet->setTitle("Inventario");

		//DEFINE EL AUTOSIZE PARA CADA COLUMNA MEN
		foreach(range('B','O') as $columnID) {
		$sheet->getColumnDimension($columnID)
		 ->setAutoSize(true);
		}
		//largo de celdas, en este caso para que la imagen quepe dentro de la celda
		foreach(range('A','A') as $columnID3) {
		 $sheet->getColumnDimension($columnID3)->setWidth(15);
		}
		//// pone el logotipo
		$sheeti = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$sheeti->setName('logo');
		$sheeti->setDescription('description');
		$sheeti->setPath('../img/logoimp.jpg');
		$sheeti->setHeight(90);
		$sheeti->setCoordinates("G1");
		$sheeti->setOffsetX(20);
		$sheeti->setOffsetY(5);
		$sheeti->setWorksheet($sheet);
		// fin logo

		$sql="SELECT
		productos_catalogo.*,
		productos.*,
		categorias.nombre as nombrecat,
		sucursal.nombre as nombresuc,
		sum(bodega.cantidad) as stock
		from productos
		LEFT OUTER JOIN productos_catalogo ON productos_catalogo.idcatalogo = productos.idcatalogo
		LEFT OUTER JOIN sucursal ON sucursal.idsucursal = productos.idsucursal
		LEFT OUTER JOIN bodega ON bodega.idproducto = productos.idproducto
		LEFT OUTER JOIN categorias ON categorias.idcat =productos_catalogo.categoria
		where productos.idsucursal='".$_SESSION['idsucursal']."' group by productos.idproducto ";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$contar=7; //empiezan los datos a partir de la fila 7

		$sheet->getStyle('A7:P7')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('04006a'); // color de celdas con rango
		$sheet->getStyle('A7:P7')->getFont()->getColor()->setARGB('fffffc'); // CAMBIAR COLOR DE LA FUENTE
		$sheet->getStyle('A1:F1')->getFont()->setSize(18); //Tamaño fuente
		$sheet->getStyle('A2')->getFont()->setSize(18); //Tamaño fuente
		$sheet->setCellValue('A2', 'Visitanos en www.sagyc.com.mx');
		$sheet->getCell('A2')->getHyperlink()->setUrl('https://www.sagyc.com.mx');

			$sheet->getStyle('A1')
		->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); //ALINEACION DE FUENTE

		$sheet->getStyle('A1')->getFont()->setBold(true); // NEGRITA
		$sheet->getStyle('A7:P7')->getFont()->setBold(true); // NEGRITA
		$sheet->mergeCells('A1:F1'); //combinar celdas
		$sheet->mergeCells('A2:E2'); //combinar celdas
		$sheet->setAutoFilter('A7:P7'); //filtro con rango
		//$sheet->getStyle('A7:O7')->getFill()->getStartColor()->setARGB('29bb04');


		$sheet->setCellValue('A'.$contar,"Imagen");
		$sheet->setCellValue('B'.$contar,"Sucursal");
		$sheet->setCellValue('C'.$contar,"Tipo");
		$sheet->setCellValue('D'.$contar,"Codigo");
		$sheet->setCellValue('E'.$contar,"Nombre");
		$sheet->setCellValue('F'.$contar,"Descripción");
		$sheet->setCellValue('G'.$contar,"Stock");
		$sheet->setCellValue('H'.$contar,"Fecha de alta");
		$sheet->setCellValue('I'.$contar,"Activo");
		$sheet->setCellValue('J'.$contar,"Categoria");
		$sheet->setCellValue('K'.$contar,"fecha mod.");
		$sheet->setCellValue('L'.$contar,"Precio");
		$sheet->setCellValue('M'.$contar,"Precio Mayoreo");
		$sheet->setCellValue('N'.$contar,"Precio Distribuidor");
		$sheet->setCellValue('O'.$contar,"Precio Super Distribuidor");
		$sheet->setCellValue('P'.$contar,"Precio Compra");


		$contar++;
		foreach($sth->fetchAll(PDO::FETCH_OBJ) as $prod){

			//BORDES dentro de un array para llenado facil
					$styleArray = [
					'borders' => [
							'allBorders' => [ // consultar parametros extras de abajo
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, //BORDER_THICK pone el borde mas grueso
									'color' => ['argb' => '04006a'], // color del borde
													],
											],
										];

										$sheet->getStyle('A'.$contar.":".'P'.$contar)->applyFromArray($styleArray);
										/* parametros extras:

										allBorders
										outline
										inside
										vertical
										horizontal
										*/
			/////

			//// inicia la carga de las imagenes de los productos, si no tiene pone una por default
			$sheeti = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
			$sheeti->setName('imagen');
			$sheeti->setDescription('description');
			if (is_null($prod->archivo)) {
			$sheeti->setPath('../img/unnamed.png');
			}
			else {
			$sheeti->setPath('../a_archivos/productos/'.$prod->archivo);
			}

			$sheeti->setHeight(40);//tamaño de la imagen
			$sheeti->setCoordinates('A'.$contar);
			$sheeti->setOffsetX(20);
			$sheeti->setOffsetY(5);
			$sheeti->setWorksheet($sheet);

			////////////////////////// fin carga imagenes

			///altura de columna/celda en este caso para que la imagen se aguste a la celda
			foreach(range('8',$contar) as $columnID2) {
			 $sheet->getRowDimension($columnID2)->setRowHeight(40);
			}
			//////////////////////
			$sheet->setCellValue('B'.$contar, $prod->nombresuc);
			if ($prod->tipo==3) {
			$sheet->setCellValue('C'.$contar, "Producto");
			}
			else {
				$sheet->setCellValue('C'.$contar, "Servicio");
			}

			$sheet->setCellValue('D'.$contar, "'".$prod->codigo);
			$sheet->setCellValue('E'.$contar, $prod->nombre);
			$sheet->setCellValue('F'.$contar, $prod->descripcion);

			$sheet->getStyle('G'.$contar)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); //ALINEACION DE FUENTE
			$sheet->getStyle('G'.$contar)->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('c8d013'); // color de celdas con rango


			$sheet->setCellValue('G'.$contar, $prod->stock);

			$sheet->setCellValue('H'.$contar, $prod->fechaalta);

			if ($prod->activo_catalogo==1) {
			$sheet->setCellValue('I'.$contar, "Si");
			}
			else {
				$sheet->setCellValue('I'.$contar, "No");
			}
			$sheet->setCellValue('J'.$contar, $prod->nombrecat);
			$sheet->setCellValue('K'.$contar, $prod->fechamod);

			$sheet->getStyle('L'.$contar.":".'p'.$contar)->getNumberFormat() // asigno formato moneda a los 3 precios
	    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
			$sheet->setCellValue('L'.$contar, $prod->precio);
			$sheet->setCellValue('M'.$contar, $prod->precio_mayoreo);
			$sheet->setCellValue('N'.$contar, $prod->precio_distri);
			$sheet->setCellValue('O'.$contar, $prod->precio_super);
			$sheet->setCellValue('P'.$contar, $prod->preciocompra);

			$contar++;
		}

		$writer = new Xlsx($spreadsheet);
		$writer->save("../".$direccion);

		echo "<div class='container text-center' style='background-color:".$_SESSION['cfondo']."; '>";
		echo "<h3>Descargar Inventario</h3>";
		echo "<a href='$direccion' target='_black' class='btn btn-success'><i class='fas fa-file-excel'></i>Excel</a>";
		echo "</div>";
	}
	public function asignar_sucursal(){
		$idcatalogo=$_REQUEST['idcatalogo'];

		$sql="select * from productos where idcatalogo='$idcatalogo' and idsucursal='".$_SESSION['idsucursal']."'";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();

		$contar=$sth->rowCount();


		if($contar==0){
			$sql="select * from productos_catalogo where idcatalogo='$idcatalogo'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$catalogo=$sth->fetch(PDO::FETCH_OBJ);
			$tipo=$catalogo->tipo;

			$arreglo =array();
			$arreglo+=array('idcatalogo'=>$idcatalogo);
			$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
			$arreglo+=array('activo_producto'=>1);
			if($tipo==0){
				$arreglo+=array('cantidad'=>1);
			}
			else{
				$arreglo+=array('cantidad'=>0);
			}

			$sql="select * from productos where idcatalogo='$idcatalogo' order by idproducto asc limit 1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			if($sth->rowCount()>0){
				$producto=$sth->fetch(PDO::FETCH_OBJ);

				$preciocompra=$producto->preciocompra;
				$precio=$producto->precio;
				$stockmin=$producto->stockmin;
				$cantidad_mayoreo=$producto->cantidad_mayoreo;
				$precio_mayoreo=$producto->precio_mayoreo;
				$precio_distri=$producto->precio_distri;
				$precio_super=$producto->precio_super;
				$mayoreo_cantidad=$producto->mayoreo_cantidad;
				$distri_cantidad=$producto->distri_cantidad;
				$esquema=$producto->esquema;
				$monto_mayor=$producto->monto_mayor;
				$monto_distribuidor=$producto->monto_distribuidor;
				$monto_super=$producto->monto_super;
			}
			else{
				$preciocompra=0;
				$precio=0;
				$stockmin=0;
				$cantidad_mayoreo=10;
				$precio_mayoreo=0;
				$precio_distri=0;
				$precio_super=0;
				$mayoreo_cantidad=0;
				$distri_cantidad=0;
				$esquema=0;
				$monto_mayor=1000;
				$monto_distribuidor=3000;
				$monto_super=5000;
				$stockmin=1;
			}

			$arreglo+=array('preciocompra'=>$preciocompra);
			$arreglo+=array('precio'=>$precio);
			$arreglo+=array('stockmin'=>$stockmin);
			$arreglo+=array('cantidad_mayoreo'=>$cantidad_mayoreo);
			$arreglo+=array('precio_mayoreo'=>$precio_mayoreo);
			$arreglo+=array('precio_distri'=>$precio_distri);
			$arreglo+=array('precio_super'=>$precio_super);
			$arreglo+=array('mayoreo_cantidad'=>$mayoreo_cantidad);
			$arreglo+=array('distri_cantidad'=>$distri_cantidad);
			$arreglo+=array('esquema'=>$esquema);
			$arreglo+=array('monto_mayor'=>$monto_mayor);
			$arreglo+=array('monto_distribuidor'=>$monto_distribuidor);
			$arreglo+=array('monto_super'=>$monto_super);
			$x=$this->insert('productos', $arreglo);
			$ped=json_decode($x);
			if($ped->error==0){
				$arreglo =array();
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>"Se agregó correctamente");
				return json_encode($arreglo);
			}
			else{
				$arreglo =array();
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Favor de verificar");
				return json_encode($arreglo);
			}
		}
		else{
			$arreglo =array();
			//$arreglo+=array('id'=>$idproducto);
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"El producto ya existe en la sucursal");
			return json_encode($arreglo);
		}
	}

	public function bodega_editar($idbodega){
		try{
			$sql="SELECT * from bodega where idbodega=:idbodega";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":idbodega",$idbodega);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function bodega_guardar(){
		$idbodega=$_REQUEST['idbodega'];

		$sql="SELECT * from bodega where idbodega=:id";
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(":id",$idbodega);
		$sth->execute();
		$bodega=$sth->fetch(PDO::FETCH_OBJ);

		$arreglo =array();
		$fecha=clean_var($_REQUEST['fecha']);
		$hora=clean_var($_REQUEST['hora']);
		$fecha=$fecha." ".$hora;
		$arreglo+=array('fecha'=>$fecha);
		$x=$this->update('bodega',array('idbodega'=>$bodega->idbodega), $arreglo);

		$ped=json_decode($x);
		if($ped->error==0){
			parent::recalcular($bodega->idproducto, "FECHA" ,$bodega->fecha);
		}

		$arreglo =array();
		$arreglo+=array('id'=>$bodega->idproducto);
		$arreglo+=array('error'=>0);
		return json_encode($arreglo);
	}
}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
