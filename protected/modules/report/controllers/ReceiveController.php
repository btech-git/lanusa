<?php

class ReceiveController extends Controller
{
	public function filters()
	{
		return array(
			'access',
		);
	}
	
	public function filterAccess($filterChain)
    {
        if ($filterChain->action->id === 'summary'
			|| $filterChain->action->id === 'ajaxHtmlSupplier')
        {
            if (!(Yii::app()->user->checkAccess('receiveReport') ))
                $this->redirect(array('/site/login'));
        }
  
        $filterChain->run();
    }
	
	public function actionSummary()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$receiveHeader = Search::bind(new ReceiveHeader('search'), isset($_GET['ReceiveHeader']) ? $_GET['ReceiveHeader'] : array());	

		$startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : '';
		$endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : '';
		$pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : '';
		$currentPage = (isset($_GET['page'])) ? $_GET['page'] : '';
		$currentSort = (isset($_GET['sort'])) ? $_GET['sort'] : '';
		
		$branchId = (isset($_GET['BranchId'])) ? $_GET['BranchId'] : '';
		$branch = Branch::model()->findByPk($branchId);
		
		$supplierId = (isset($_GET['SupplierId'])) ? $_GET['SupplierId'] : '';
		$suppliers = Supplier::model()->findAllByAttributes(
			array(
				'branch_id' => $branchId
			),
			array(
				'order' => 'name ASC'
			)
		);
		
		$receiveSummary = new ReceiveSummary($receiveHeader->search());
		$receiveSummary->setupLoading();
		$receiveSummary->setupPaging($pageSize, $currentPage);
		$receiveSummary->setupSorting();
		$filters = array(
			'startDate' => $startDate,
			'endDate' => $endDate,
			'branchId' => $branchId,
			'supplierId' => $supplierId
		);
		$receiveSummary->setupFilter($filters);
		 
                if (isset($_GET['SaveExcel']))
			$this->saveToExcel($receiveSummary, $branch, $receiveSummary->dataProvider, array('startDate' => $startDate, 'endDate' => $endDate));
		
		$this->render('summary', array(
			'receiveHeader' => $receiveHeader,
			'receiveSummary' => $receiveSummary,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'currentSort' => $currentSort,
			'branchId' => $branchId,
			'branch' => $branch,
			'supplierId' => $supplierId,
			'suppliers' => $suppliers
		));
	}
	
	public function actionAjaxHtmlSupplier()									//find supplier based on selected branch
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$supplierId = '';
			$branchId = (isset($_POST['BranchId'])) ? $_POST['BranchId'] : '';
			
			$suppliers = Supplier::model()->findAllByAttributes(
				array(
					'branch_id' => $branchId
				),
				array(
					'order' => 'name ASC'
				)
			);

			$this->renderPartial('_supplier', array(
				'suppliers' => $suppliers,
				'supplierId' => $supplierId
			));
		}
	}
        
        protected function saveToExcel($receiveSummary, $branch, $dataProvider, array $options = array())
	{
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
		spl_autoload_register(array('YiiBase', 'autoload'));

		$objPHPExcel = new PHPExcel();

		$documentProperties = $objPHPExcel->getProperties();
		$documentProperties->setCreator('Lanusa');
		$documentProperties->setTitle('Laporan Penerimaan Barang');

		$worksheet = $objPHPExcel->setActiveSheetIndex(0);
		$worksheet->setTitle('Penerimaan');

		$worksheet->getColumnDimension('A')->setAutoSize(true);
		$worksheet->getColumnDimension('B')->setAutoSize(true);
		$worksheet->getColumnDimension('C')->setAutoSize(true);
		$worksheet->getColumnDimension('D')->setAutoSize(true);
		$worksheet->getColumnDimension('E')->setAutoSize(true);
		$worksheet->getColumnDimension('F')->setAutoSize(true);
	
		$worksheet->mergeCells('A1:F1');
		$worksheet->mergeCells('A2:F2');
		$worksheet->mergeCells('A3:F3');

		$worksheet->getStyle('A1:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$worksheet->getStyle('A1:F6')->getFont()->setBold(true);

		$worksheet->setCellValue('A1',  CHtml::encode(CHtml::value($branch, 'name')));
		$worksheet->setCellValue('A2', 'Laporan Penerimaan Barang');
		$worksheet->setCellValue('A3', Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['startDate'])) . ' - ' . Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($options['endDate'])));

                $worksheet->getStyle('A5:F5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
              
		$worksheet->setCellValue('A5', 'Penerimaan #');
		$worksheet->setCellValue('B5', 'Tanggal');
                $worksheet->setCellValue('C5', 'Pembelian #');
                $worksheet->setCellValue('D5', 'Faktur #');
                $worksheet->setCellValue('E5', 'Supplier');
                $worksheet->setCellValue('F5', 'Gudang');
       
                $worksheet->mergeCells('A6:C6');
		$worksheet->setCellValue('A6', 'Nama Barang');
		$worksheet->setCellValue('D6', 'Ukuran');
		$worksheet->setCellValue('E6', 'Satuan');
		$worksheet->setCellValue('F6', 'Jumlah Terima');

                $worksheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

		$counter = 7;
		foreach ($dataProvider->data as $header)
		{
			$worksheet->getStyle("A{$counter}:F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
             
                        $worksheet->setCellValue("A{$counter}", CHtml::encode($header->getCodeNumber(ReceiveHeader::CN_CONSTANT)));
                        $worksheet->setCellValue("B{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))));
                        $worksheet->setCellValue("C{$counter}", CHtml::encode($header->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)));
                        $worksheet->setCellValue("D{$counter}", nl2br(CHtml::encode(CHtml::value($header, 'reference'))));
                        $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($header, ($header->is_non_tax) ? 'purchaseHeader.supplier.name' : 'purchaseHeader.supplier.company')));
                        $worksheet->setCellValue("F{$counter}", CHtml::encode(CHtml::value($header, 'warehouse.name')));
                        
                        $counter++;
                        
			foreach ($header->receiveDetails as $detail)
			{
                                $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                                $worksheet->getStyle("F{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                $worksheet->mergeCells("A{$counter}:C{$counter}");
                                $worksheet->setCellValue("A{$counter}", CHtml::encode(CHtml::value($detail, 'product.name')));
                                $worksheet->setCellValue("D{$counter}", htmlspecialchars_decode(CHtml::encode(CHtml::value($detail, 'product.size'))));
                                $worksheet->setCellValue("E{$counter}", CHtml::encode(CHtml::value($detail, 'product.unit.name')));
                                $worksheet->setCellValue("F{$counter}",CHtml::encode(CHtml::value($detail, 'quantity')));
                                $counter++;
                            
			}
               
                ;
                         $counter++;
		}

		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment;filename="Laporan Penerimaan.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		Yii::app()->end();
	}
}
