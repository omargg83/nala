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
		if(isset($_SESSION['idusuario']) and $_SESSION['autoriza'] == 1 and array_key_exists('PRODUCTOS', $this->derecho)) {

			////////////////PERMISOS
			$sql="SELECT nivel,captura FROM usuarios_permiso where idusuario='".$_SESSION['idusuario']."' and modulo='PRODUCTOS'";
			$stmt= $this->dbh->query($sql);

			$row =$stmt->fetchObject();
			$this->nivel_personal=$row->nivel;
			$this->nivel_captura=$row->captura;
		}
		else{
			include "../error.php";
			die();
		}
	}
	public function producto_buscar($texto){
		$sql="select * from productos_catalogo where (productos_catalogo.nombre like '%$texto%' or productos_catalogo.codigo like '%$texto%') and idtienda='".$_SESSION['idtienda']."' limit 50";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_OBJ);
  }
	public function productos_lista($pagina){
		try{
			$pagina=$pagina*$_SESSION['pagina'];
			$sql="SELECT * from productos_catalogo where activo_catalogo=1 order by nombre asc, idcatalogo asc limit $pagina,".$_SESSION['pagina']."";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function productos_homologar($idcatalogo){
		try{
			$sql="SELECT * from productos_catalogo where idcatalogo!=$idcatalogo order by idcatalogo asc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function borrar_producto(){

		if (isset($_REQUEST['idcatalogo'])){ $idcatalogo=$_REQUEST['idcatalogo']; }

		$sql="select * from productos_catalogo where idcatalogo=:id";
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(":id",$idcatalogo);
		$sth->execute();
		$prod=$sth->fetch(PDO::FETCH_OBJ);

		$ruta = '../'.$this->f_productos.$prod->archivo;
		if (file_exists($ruta)){
			unlink($ruta);
		}
		return $this->borrar('productos_catalogo',"idcatalogo",$idcatalogo);
	}
	public function foto(){
		$x="";
		$arreglo =array();
		$idcatalogo=$_REQUEST['idcatalogo'];

		$sql="select * from productos_catalogo where idcatalogo=:id";
		$sth = $this->dbh->prepare($sql);
		$sth->bindValue(":id",$idcatalogo);
		$sth->execute();
		$prod=$sth->fetch(PDO::FETCH_OBJ);

		if(strlen($prod->archivo)>0){
			$ruta = '../'.$this->f_productos.$prod->archivo;
			if (file_exists($ruta)){
				unlink($ruta);
			}
		}
		$extension = '';
		$ruta = '../'.$this->f_productos;
		$archivo = $_FILES['foto']['tmp_name'];
		$nombrearchivo = $_FILES['foto']['name'];
		$tmp=$_FILES['foto']['tmp_name'];
		$info = pathinfo($nombrearchivo);
		if($archivo!=""){
			$extension = $info['extension'];
			if ($extension=='png' || $extension=='PNG' || $extension=='jpg'  || $extension=='JPG') {
				$nombreFile = "resp_".date("YmdHis").rand(0000,9999).".".$extension;
				move_uploaded_file($tmp,$ruta.$nombreFile);
				$ruta=$ruta."/".$nombreFile;
				$arreglo+=array('archivo'=>$nombreFile);
			}
			else{
				echo "fail";
				exit;
			}
		}
		return $this->update('productos_catalogo',array('idcatalogo'=>$idcatalogo), $arreglo);
	}
	public function producto_edit($id){
		try{
			$sql="select * from productos_catalogo where idcatalogo=:id and idtienda='".$_SESSION['idtienda']."'";
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
			if (isset($_REQUEST['idcatalogo'])){
				$idcatalogo=$_REQUEST['idcatalogo'];
			}
			$arreglo =array();
			$tipo="";
			$imei="";
			$codigo="";

			if (isset($_REQUEST['codigo'])){
				$codigo=$_REQUEST['codigo'];
				$arreglo += array('codigo'=>$_REQUEST['codigo']);
			}
			if (isset($_REQUEST['nombre'])){
				$arreglo += array('nombre'=>$_REQUEST['nombre']);
			}

			if (isset($_REQUEST['descripcion'])){
				$arreglo += array('descripcion'=>$_REQUEST['descripcion']);
			}
			if (isset($_REQUEST['activo_catalogo'])){
				$arreglo += array('activo_catalogo'=>$_REQUEST['activo_catalogo']);
			}
			if (isset($_REQUEST['categoria'])){
				$arreglo += array('categoria'=>$_REQUEST['categoria']);
			}

			if (isset($_REQUEST['tipo'])){
				$tipo=$_REQUEST['tipo'];
				$arreglo += array('tipo'=>$_REQUEST['tipo']);
			}
			$x="";

			if($idcatalogo==0){
				$arreglo+=array('fechaalta'=>date("Y-m-d H:i:s"));
				$arreglo+=array('idtienda'=>$_SESSION['idtienda']);
				$x=$this->insert('productos_catalogo', $arreglo);
				$ped=json_decode($x);

				if($ped->error==0){
					$idcatalogo=$ped->id;

					/*
					$arreglo =array();
					if($tipo==0){
						$arreglo+=array('cantidad'=>1);
					}
					$monto_mayor=1000;
					$monto_distribuidor=3000;
					$stockmin=1;
					$cantidad_mayoreo=10;

					$arreglo+=array('preciocompra'=>0);
					$arreglo+=array('precio'=>0);
					$arreglo+=array('monto_mayor'=>$monto_mayor);

					$arreglo+=array('monto_distribuidor'=>$monto_distribuidor);
					$arreglo+=array('stockmin'=>$stockmin);
					$arreglo+=array('cantidad_mayoreo'=>$cantidad_mayoreo);
					$arreglo+=array('mayoreo_cantidad'=>0);
					$arreglo+=array('distri_cantidad'=>0);
					$arreglo+=array('precio_mayoreo'=>0);
					$arreglo+=array('precio_distri'=>0);

					$arreglo+=array('idcatalogo'=>$idcatalogo);
					$arreglo+=array('idsucursal'=>$_SESSION['idsucursal']);
					$this->insert('productos', $arreglo);

					$this->cantidad_update($idcatalogo,$tipo);
					*/

					if(strlen(trim($codigo))==0){
						$codigo="9".str_pad($idcatalogo, 8, "0", STR_PAD_LEFT);
						$arreglo =array();
						$arreglo = array('codigo'=>$codigo);
						$this->update('productos_catalogo',array('idcatalogo'=>$idcatalogo), $arreglo);
					}
				}
				else{
					return $x;
				}
			}
			else{
				$arreglo+=array('fechamod'=>date("Y-m-d H:i:s"));
				$x=$this->update('productos_catalogo',array('idcatalogo'=>$idcatalogo), $arreglo);

				$this->cantidad_update($idcatalogo,$tipo);
			}
			return $x;
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
			$x=$this->update('productos_catalogo',array('id'=>$id), $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
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
	public function excel(){
		$direccion="tmp/Catalogo_porductos.xlsx";

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1',"Catalogo de productos");
		$sheet->setTitle("Catalogo de prodcutos");

		//DEFINE EL AUTOSIZE PARA CADA COLUMNA MEN
		foreach(range('B','O') as $columnID) {
    $sheet->getColumnDimension($columnID)
     ->setAutoSize(true);
	 	}
		//largo de celdas
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
		tienda.razon,
		categorias.nombre as nombrecat
		from productos_catalogo
		LEFT OUTER JOIN categorias ON categorias.idcat =productos_catalogo.categoria
		LEFT OUTER JOIN tienda ON tienda.idtienda =productos_catalogo.idtienda
		where productos_catalogo.idtienda='".$_SESSION['idtienda']."' and productos_catalogo.activo_catalogo=1 order by productos_catalogo.nombre asc, productos_catalogo.idcatalogo asc";


		//	$sql="SELECT * from productos_catalogo where activo_catalogo=1 order by nombre asc, idcatalogo asc";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$contar=7;

		$sheet->getStyle('A7:O7')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('04006a'); // color de celdas con rango
		$sheet->getStyle('A7:O7')->getFont()->getColor()->setARGB('fffffc'); // CAMBIAR COLOR DE LA FUENTE
		$sheet->getStyle('A1:F1')->getFont()->setSize(18); //Tamaño fuente
		$sheet->getStyle('A2')->getFont()->setSize(18); //Tamaño fuente
		$sheet->setCellValue('A2', 'Visitanos en www.sagyc.com.mx');
		$sheet->getCell('A2')->getHyperlink()->setUrl('https://www.sagyc.com.mx');

			$sheet->getStyle('A1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); //ALINEACION DE FUENTE

		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->getStyle('A7:O7')->getFont()->setBold(true); // NEGRITA
		$sheet->mergeCells('A1:F1'); //combinar celdas
		$sheet->mergeCells('A2:E2'); //combinar celdas
		$sheet->setAutoFilter('A7:O7'); //filtro
		//$sheet->getStyle('A7:O7')->getFill()->getStartColor()->setARGB('29bb04');


		$sheet->setCellValue('A'.$contar,"Imagen");
		$sheet->setCellValue('B'.$contar,"Id catalogo");
		$sheet->setCellValue('C'.$contar,"Tienda");
		$sheet->setCellValue('D'.$contar,"Tipo");
		$sheet->setCellValue('E'.$contar,"Codigo");
		$sheet->setCellValue('F'.$contar,"Nombre");
		$sheet->setCellValue('G'.$contar,"Descripción");
		$sheet->setCellValue('H'.$contar,"Unidad");
		$sheet->setCellValue('I'.$contar,"Color");
		$sheet->setCellValue('J'.$contar,"Marca");
		$sheet->setCellValue('K'.$contar,"Modelo");
		$sheet->setCellValue('L'.$contar,"Fecha de alta");
		$sheet->setCellValue('M'.$contar,"Activo");
		$sheet->setCellValue('N'.$contar,"Categoria");
		$sheet->setCellValue('O'.$contar,"fecha mod.");
		$contar++;
		foreach($sth->fetchAll(PDO::FETCH_OBJ) as $prod){

			//BORDES
					$styleArray = [
			    'borders' => [
			        'allBorders' => [
			            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, //BORDER_THICK pone el borde mas grueso
			            'color' => ['argb' => '04006a'],
			        						],
			    						],
										];

										$sheet->getStyle('A'.$contar.":".'O'.$contar)->applyFromArray($styleArray);
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

			$sheeti->setHeight(40);
			$sheeti->setCoordinates('A'.$contar);
			$sheeti->setOffsetX(20);
			$sheeti->setOffsetY(5);
			$sheeti->setWorksheet($sheet);
			///altura de columna
			foreach(range('8',$contar) as $columnID2) {
			 $sheet->getRowDimension($columnID2)->setRowHeight(40);
			}
			//////////////////////
			$sheet->setCellValue('B'.$contar, $prod->idcatalogo);
			$sheet->setCellValue('C'.$contar, $prod->razon);
			if ($prod->tipo==3) {
			$sheet->setCellValue('D'.$contar, "Producto");
			}
			else {
				$sheet->setCellValue('D'.$contar, "Servicio");
			}

			$sheet->setCellValue('E'.$contar, "'".$prod->codigo);
			$sheet->setCellValue('F'.$contar, $prod->nombre);
			$sheet->setCellValue('G'.$contar, $prod->descripcion);
			$sheet->setCellValue('H'.$contar, $prod->unidad);
			$sheet->setCellValue('I'.$contar, $prod->color);
			$sheet->setCellValue('J'.$contar, $prod->marca);
			$sheet->setCellValue('K'.$contar, $prod->modelo);
			$sheet->setCellValue('L'.$contar, $prod->fechaalta);

			if ($prod->activo_catalogo==1) {
			$sheet->setCellValue('M'.$contar, "Si");
			}
			else {
				$sheet->setCellValue('M'.$contar, "No");
			}
			$sheet->setCellValue('N'.$contar, $prod->nombrecat);
			$sheet->setCellValue('O'.$contar, $prod->fechamod);

			$contar++;
		}

		$writer = new Xlsx($spreadsheet);
		$writer->save("../".$direccion);

		echo "<div class='container text-center' style='background-color:".$_SESSION['cfondo']."; '>";
		echo "<h3>Descargar Catalogo</h3>";
		echo "<a href='$direccion' target='_black' class='btn btn-success'><i class='fas fa-file-excel'></i>Excel</a>";
		echo "</div>";
	}
	public function homologa_final(){
		$origen=$_REQUEST['origen'];
		$destino=$_REQUEST['destino'];

		$sql="select * from productos where idcatalogo=$destino";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		foreach($sth->fetchAll(PDO::FETCH_OBJ) as $hom){
			$arreglo =array();
			$arreglo += array('idcatalogo'=>$origen);
			$this->update('productos',array('idproducto'=>$hom->idproducto), $arreglo);

		}
		$x=$this->borrar('productos_catalogo',"idcatalogo",$destino);
		return $x;
	}

}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
