<?php

class HppController extends Controller
{
	
	public function filters()
	{
		return array(
			'access',
		);
	}

	public function filterAccess($filterChain)
	{
		if ($filterChain->action->id === 'summary')
		{
			if (!(Yii::app()->user->checkAccess('allAccountingReport')))
				$this->redirect(array('/site/login'));
		}

		$filterChain->run();
	}
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$product = Search::bind(new Product('search'), isset($_GET['Product']) ? $_GET['Product'] : array());
		
		$listData = CHtml::listData(Branch::model()->findAll(), 'id', 'name');
		
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
		$productName = (isset($_GET['Product']['name'])) ? $_GET['Product']['name'] : '';
		$productCategoryId = (isset($_GET['Product']['category_id'])) ? $_GET['Product']['category_id'] : '';

//		$dataProvider = $product->search();
////                $dataProvider->criteria->join = "INNER JOIN (".SqlViewGenerator::globalStock().") v ON t.id = v.product_id";
//		$dataProvider->criteria->with = array('category');
////                $dataProvider->criteria->addCondition("v.quantity_current > 0");
//
//		$page = array('size' => $pageSize, 'current' => $currentPage);
//
//		$sort = new CSort(get_class($product));
//		$sort->attributes = array('t.name', 'category.name');
//
//		$dataProvider = ReportHelper::finalizeDataProvider($dataProvider, $page, $sort);

//         
//                       $warehouseId = (isset($_GET['WarehouseId'])) ? $_GET['WarehouseId'] : 1;
			
		$hppSummary = new HppSummary($product->searchByCogs());
		$hppSummary->setupLoading();
		$hppSummary->setupPaging($pageSize, $currentPage);
		$hppSummary->setupSorting();
	
		$hppSummary->setupFilter($productName, $productCategoryId);
                
                 if (isset($_GET['SaveExcel']))
                        $this->saveToExcel($hppSummary, $hppSummary->dataProvider);
		
		
		
		$this->render('summary', array(
			'product' => $product,
			'hppSummary' => $hppSummary,
			'currentSort' => $currentSort,
//          'warehouseId'=>$warehouseId,
			'listData' => $listData,

		));
	}
	
	protected function reportGrandTotal($dataProvider)
	{
		$grandTotal = 0.00;

		foreach ($dataProvider->data as $data)
			$grandTotal += $data->costOfGoodsSold;

		return $grandTotal;
	}
        
        protected function saveToExcel($hppSummary, $dataProvider)
	{
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
		spl_autoload_register(array('YiiBase', 'autoload'));

		$objPHPExcel = new PHPExcel();

		$documentProperties = $objPHPExcel->getProperties();
		$documentProperties->setCreator('Lanusa');
		$documentProperties->setTitle('Laporan Harga Pokok Penjualan');

		$worksheet = $objPHPExcel->setActiveSheetIndex(0);
		$worksheet->setTitle('Laporan HPP');

		$worksheet->getColumnDimension('A')->setAutoSize(true);
		$worksheet->getColumnDimension('B')->setAutoSize(true);
		$worksheet->getColumnDimension('C')->setAutoSize(true);
		$worksheet->getColumnDimension('D')->setAutoSize(true);
		$worksheet->getColumnDimension('E')->setAutoSize(true);
              
		$worksheet->mergeCells('A1:E1');
		$worksheet->mergeCells('A2:E2');
		$worksheet->mergeCells('A3:E3');

		$worksheet->getStyle('A1:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A1:E5')->getFont()->setBold(true);

                
		$worksheet->setCellValue('A1',  '');
		$worksheet->setCellValue('A2', 'Laporan Harga Pokok Penjualan');
		$worksheet->setCellValue('A3', '');

                $worksheet->getStyle('A5:E5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
                
		
		$worksheet->setCellValue('A5', 'Kategori');
		$worksheet->setCellValue('B5', 'Nama Produk');
                $worksheet->setCellValue('C5', 'Ukuran');
                $worksheet->setCellValue('D5', 'Stok');
                $worksheet->setCellValue('E5', 'Hpp (Rp)');
               
       
                $worksheet->getStyle('A5:E5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

		$counter = 6;
		foreach ($dataProvider->data as $header)
		{
			$worksheet->getStyle("A{$counter}:B{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $worksheet->getStyle("C{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($header, 'category.name')));
                        $worksheet->setCellValue("B{$counter}", CHtml::encode(CHtml::value($header, 'name')));
                        $worksheet->setCellValue("C{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($header, 'size'))));
                        $worksheet->setCellValue("D{$counter}", CHtml::encode(ceil($header->getGlobalStock())));
                        $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, 'costOfGoodsSold')));
                        $counter++;

		}
                  
                
                $worksheet->getStyle("A{$counter}:E{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
                $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
                $worksheet->setCellValue("D{$counter}", 'TOTAL HPP');
                $worksheet->setCellValue("E{$counter}",CHtml::encode(ceil($this->reportGrandTotal($hppSummary->dataProvider))));  
              
                
		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment;filename="Laporan Harga Pokok Penjualan.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		Yii::app()->end();
	}
}
