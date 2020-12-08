<?php
	require_once("db_.php");

		require '../vendor/autoload.php';

		use PhpOffice\PhpSpreadsheet\Spreadsheet;
		use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
		$idsucursal=$_REQUEST['idsucursal'];

		print_r($idsucursal);
		$direccion="tmp/Inventario_por_sucursal.xlsx";

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1',"Inventario por sucursal");
		$sheet->setTitle("Inventario");

		//DEFINE EL AUTOSIZE PARA CADA COLUMNA MEN
		foreach(range('B','N') as $columnID) {
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
		where productos.idsucursal=:idsucursal group by productos.idproducto ";
		$sth = $db->dbh->prepare($sql);
		$sth->bindValue(":idsucursal",$idsucursal);
		$sth->execute();
		$contar=7; //empiezan los datos a partir de la fila 7

		$sheet->getStyle('A7:N7')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('04006a'); // color de celdas con rango
		$sheet->getStyle('A7:N7')->getFont()->getColor()->setARGB('fffffc'); // CAMBIAR COLOR DE LA FUENTE
		$sheet->getStyle('A1:F1')->getFont()->setSize(18); //Tamaño fuente
		$sheet->getStyle('A2')->getFont()->setSize(18); //Tamaño fuente
		$sheet->setCellValue('A2', 'Visitanos en www.sagyc.com.mx');
		$sheet->getCell('A2')->getHyperlink()->setUrl('https://www.sagyc.com.mx');

			$sheet->getStyle('A1')
		->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); //ALINEACION DE FUENTE

		$sheet->getStyle('A1')->getFont()->setBold(true); // NEGRITA
		$sheet->getStyle('A7:N7')->getFont()->setBold(true); // NEGRITA
		$sheet->mergeCells('A1:F1'); //combinar celdas
		$sheet->mergeCells('A2:E2'); //combinar celdas
		$sheet->setAutoFilter('A7:N7'); //filtro con rango
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

										$sheet->getStyle('A'.$contar.":".'N'.$contar)->applyFromArray($styleArray);
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

			$sheet->getStyle('L'.$contar.":".'N'.$contar)->getNumberFormat() // asigno formato moneda a los 3 precios
			->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
			$sheet->setCellValue('L'.$contar, $prod->precio);
			$sheet->setCellValue('M'.$contar, $prod->precio_mayoreo);
			$sheet->setCellValue('N'.$contar, $prod->precio_distri);

			$contar++;
		}

		$writer = new Xlsx($spreadsheet);
		$writer->save("../".$direccion);

		echo "<div class='container text-center' style='background-color:".$_SESSION['cfondo']."; '>";
		echo "<h3>Descargar Inventario</h3>";
		echo "<a href='$direccion' target='_black' class='btn btn-success'><i class='fas fa-file-excel'></i>Excel</a>";
		echo "</div>";

 ?>
