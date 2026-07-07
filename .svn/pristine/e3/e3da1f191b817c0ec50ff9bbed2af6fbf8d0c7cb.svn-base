<?php

class AgingPayableSummary extends CComponent {

    public $dataProvider;

    public function __construct($dataProvider) {
        $this->dataProvider = $dataProvider;
    }

    public function setupLoading() {
        $this->dataProvider->criteria->with = array(
            'supplier:resetScope',
            'branch:resetScope',
        );
        $this->dataProvider->criteria->compare('t.is_inactive', 0);
//		$dataProvider->criteria->addCondition(SqlViewGenerator::agingPayable());
//		$dataProvider->criteria->compare('purchaseHeader.supplier_id', $supplierId);
//		$dataProvider->criteria->join = "INNER JOIN " . PurchaseHeader::model()->tableName() . " purchaseHeader ON purchaseHeader.id = t.purchase_header_id INNER JOIN " . Supplier::model()->tableName() . " supplier ON purchaseHeader.supplier_id = supplier.id
//										LEFT OUTER JOIN ". PurchaseReceiptDetail::model()->tableName() ." purchaseReceiptDetail ON t.id = purchaseReceiptDetail.purchase_invoice_id ";
//			'purchaseReceiptDetails:resetScope'=>array(
//				'with'=>array('purchaseReceiptHeader:resetScope'),
//			),
//			'purchaseInvoiceDetails:resetScope'=>array(
//				'with' => array('purchaseHeader:resetScope'),
//			),
//			'supplier:resetScope',
//		);
    }

    public function setupPaging($pageSize, $currentPage) {
        $pageSize = (empty($pageSize)) ? 10 : $pageSize;
        $pageSize = ($pageSize <= 0) ? 1 : $pageSize;
        $this->dataProvider->pagination->pageSize = $pageSize;

        $currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
        $this->dataProvider->pagination->currentPage = $currentPage;
    }

    public function setupSorting() {
        $this->dataProvider->sort->attributes = array('t.date', 'supplier.company');
        $this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
    }

    public function setupFilter($startDate, $endDate) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
        $this->dataProvider->criteria->addBetweenCondition('t.date', $startDate, $endDate);
//		$this->dataProvider->criteria->compare('supplier_id', $supplierId);
    }

    public function setupBranch($branch) {
        $this->dataProvider->criteria->compare('t.branch_id', $branch);
    }

    public function getGrandTotal() {
        $grandTotal = 0.00;

        foreach ($this->dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

}
