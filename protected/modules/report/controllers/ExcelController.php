<?php

class ExcelController extends Controller
{

	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$this->saveToExcel();
	}

	public function saveToExcel()
	{
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
		spl_autoload_register(array('YiiBase', 'autoload'));

		$objPHPExcel = new PHPExcel();

		$documentProperties = $objPHPExcel->getProperties();
		$documentProperties->setCreator('PT. LANUSA');
		$documentProperties->setTitle('Laporan Stok Barang');

		$sql = SqlViewGenerator::excelCategorySize();
		$sizeProducts = Yii::app()->db->createCommand($sql)->queryAll();//CActiveRecord::$db->createCommand($sql)->queryAll();

		$sql = SqlViewGenerator::excelGlobalStock();
		$products = Yii::app()->db->createCommand($sql)->queryAll();//CActiveRecord::$db->createCommand($sql)->queryAll();

		$sheetIndex = 0;
		$oldCategoryId = 0;
		$oldName = 0;
		$rowNumber = 1;
		foreach ($products as $product)
		{
			if ($oldCategoryId !== $product['category_id'])
			{
				$oldName = 0;
				$rowNumber = 1;

				if ($sheetIndex > 0)
					$objPHPExcel->createSheet();

				$worksheet = $objPHPExcel->setActiveSheetIndex($sheetIndex++);
				$worksheet->setTitle($product['category_name']);
				$worksheet->getColumnDimension('A')->setAutoSize(true);
				$worksheet->getRowDimension($rowNumber)->setRowHeight(18.0);

				$sizes = array();
				foreach ($sizeProducts as $sizeProduct)
				{
					if ($sizeProduct['category_id'] === $product['category_id'])
						$sizes[] = $sizeProduct['size'];
				}
				foreach ($sizes as $i => $size)
				{
					$columnName = $this->toExcelColumnName($i + 2);
					$worksheet->getColumnDimension($columnName)->setAutoSize(true);
					$worksheet->getStyle("{$columnName}{$rowNumber}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$worksheet->getStyle("{$columnName}{$rowNumber}")->getFont()->setBold(true)->setSize(12);
					$worksheet->setCellValue("{$columnName}1", $size);
				}
			}

			if ($oldName !== $product['name'])
			{
				$rowNumber++;
				$worksheet->getRowDimension($rowNumber)->setRowHeight(16.0);
				$worksheet->getStyle("A{$rowNumber}")->getFont()->setBold(true);
				$worksheet->setCellValue("A{$rowNumber}", $product['name']);
			}

			$columnName = $this->toExcelColumnName(array_search($product['size'], $sizes) + 2);
			if (intval($product['current_stock']) !== 0)
			{
				$worksheet->getStyle("{$columnName}{$rowNumber}")->getNumberFormat()->setFormatCode('#,##0');
				$worksheet->setCellValue("{$columnName}{$rowNumber}", $product['current_stock']);
			}

			$oldCategoryId = $product['category_id'];
			$oldName = $product['name'];
		}

		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment;filename="stok.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		Yii::app()->end();
	}

	public function toExcelColumnName($columnNumber)
	{
		$dividend = $columnNumber;
		$columnName = '';

		while ($dividend > 0)
		{
			$modulo = ($dividend - 1) % 26;
			$columnName = chr(65 + $modulo) . $columnName;
			$dividend = intval(($dividend - $modulo) / 26);
		}

		return $columnName;
	}
}