<?php

class BankBookController extends Controller {

    public function filters() {
        return array(
            'access',
        );
    }

    public function filterAccess($filterChain) {
        if ($filterChain->action->id === 'summary' || $filterChain->action->id === 'ajaxHtmlAccount') {
            if (!(Yii::app()->user->checkAccess('cashExpenseReport') || Yii::app()->user->checkAccess('cashDepositReport')))
                $this->redirect(array('/site/login'));
        }

        $filterChain->run();
    }

    public function actionSummary() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());
        $branch = Search::bind(new Branch('search'), isset($_GET['Branch']) ? $_GET['Branch'] : array());

        $startDate = (isset($_GET['StartDate'])) ? $_GET['StartDate'] : date('Y-m-d');
        $endDate = (isset($_GET['EndDate'])) ? $_GET['EndDate'] : date('Y-m-d');
        $accountId = (isset($_GET['AccountId'])) ? $_GET['AccountId'] : '';
        $branchId = (isset($_GET['Branch']['id'])) ? $_GET['Branch']['id'] : '';
        $branchName = Branch::model()->findByPk($branchId);
        $accountName = Account::model()->findByPk($accountId);
        $pageSize = (isset($_GET['PageSize'])) ? $_GET['PageSize'] : 10;
        $currentPage = (isset($_GET['CurrentPage'])) ? $_GET['CurrentPage'] - 1 : 0;

        $accounts = Account::model()->findAllByAttributes(array(
            'branch_id' => $branchId
        ), array(
            'order' => 'name ASC',
            'condition' => 'account_category_id < 3',
        ));

        $sql = SqlGenerator::bankBook();
        $params = array(
            ':account_id' => $accountId,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':branch_id' => $branchId
        );

        $dataProvider = new CSqlDataProvider($sql, array(
            'db' => CActiveRecord::$db,
            'params' => $params,
            'totalItemCount' => CActiveRecord::$db->createCommand(SqlViewGenerator::count($sql))->queryScalar($params),
            'pagination' => array(
                'pageVar' => 'CurrentPage',
                'pageSize' => ($pageSize > 0) ? $pageSize : 1,
                'currentPage' => $currentPage,
            ),
        ));

        if (isset($_GET['SaveExcel'])) {
            $this->saveToExcel($dataProvider, $startDate, $endDate, $branchName, $accountName, $accountId, $branchId, $account);
        }

        $this->render('summary', array(
            'account' => $account,
            'branch' => $branch,
            'dataProvider' => $dataProvider,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'accountId' => $accountId,
            'accountName' => $accountName,
            'accounts' => $accounts,
            'branchId' => $branchId,
            'branchName' => $branchName
        ));
    }

    public function actionAjaxHtmlAccount() {
        if (Yii::app()->request->isAjaxRequest) {

            $accountId = '';
            $accounts = Account::model()->findAllByAttributes(array(
                'branch_id' => $_POST['BranchId'],
            ),array(
                'order' => 'name ASC',
                'condition' => 'account_category_id < 3',
            ));

            $account = Search::bind(new Account('search'), isset($_GET['Account']) ? $_GET['Account'] : array());

            $this->renderPartial('_account', array(
                'account' => $account,
                'accounts' => $accounts,
                'accountId' => $accountId,
            ));
        }
    }

    protected function saveToExcel($dataProvider, $startDate, $endDate, $branchName, $accountName, $accountId, $branchId, $account) {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        
        $startDateFormatted = Yii::app()->dateFormatter->format('d MMMM yyyy', $startDate);
        $endDateFormatted = Yii::app()->dateFormatter->format('d MMMM yyyy', $endDate);

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once Yii::getPathOfAlias('ext.phpexcel.Classes') . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();

        $documentProperties = $objPHPExcel->getProperties();
        $documentProperties->setCreator('Lanusa');
        $documentProperties->setTitle('Laporan Buku Kas');

        $worksheet = $objPHPExcel->setActiveSheetIndex(0);
        $worksheet->setTitle('Laporan Buku Kas');

        $worksheet->mergeCells('A1:E1');
        $worksheet->mergeCells('A2:E2');
        $worksheet->mergeCells('A3:E3');
        $worksheet->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1:E3')->getFont()->setBold(true);
        $worksheet->setCellValue('A1', CHtml::encode(CHtml::value($branchName, 'name')));
        $worksheet->setCellValue('A2', 'Laporan' . CHtml::encode(CHtml::value($accountName, 'name')));
        $worksheet->setCellValue('A3', $startDateFormatted . ' - ' . $endDateFormatted);

        $worksheet->mergeCells('A4:E4');
        $worksheet->mergeCells('A5:E5');

        $worksheet->getStyle("A6:E6")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $worksheet->getStyle("A6:E6")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle('A6:E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A6:E6')->getFont()->setBold(true);
        $worksheet->setCellValue('A6', 'Tanggal');
        $worksheet->setCellValue('B6', 'No Perkiraan');
        $worksheet->setCellValue('C6', 'Keterangan');
        $worksheet->setCellValue('D6', 'Debit');
        $worksheet->setCellValue('E6', 'Kredit');

        $counter = 7;

        $worksheet->getStyle("A{$counter}:E{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", 'Saldo Awal');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($account->getBeginningBalance($accountId, $startDate, $branchId)));
        $counter++;

        foreach ($dataProvider->data as $data) {
            $accountDetail = Account::model()->findByPk($data['detail_account_id']);
            $worksheet->setCellValue("A{$counter}", CHtml::encode(Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($data['date']))));
            $worksheet->setCellValue("B{$counter}", CHtml::encode($accountDetail->code));
            $worksheet->setCellValue("C{$counter}", CHtml::encode(CHtml::value($data, 'note')));
            $worksheet->getStyle("D{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue("D{$counter}", CHtml::encode($data['debit']));
            $worksheet->setCellValue("E{$counter}", CHtml::encode($data['credit']));

            $counter++;
        }

        $worksheet->getStyle("A{$counter}:E{$counter}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $worksheet->getStyle("A{$counter}:E{$counter}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $worksheet->setCellValue("C{$counter}", 'Saldo Akhir');
        $worksheet->setCellValue("D{$counter}", CHtml::encode($account->getEndingBalance($accountId, $endDate, $branchId)));

        $counter++;

        for ($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/xlsx');
        header('Content-Disposition: attachment;filename="Laporan Buku Kas.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        Yii::app()->end();
    }
}
