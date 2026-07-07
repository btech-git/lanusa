<?php

class SaleItemSummary extends CComponent {

    public $dataProvider;

    public function __construct($dataProvider) {
        $this->dataProvider = $dataProvider;
    }

    public function setupLoading() {
        $this->dataProvider->criteria->together = TRUE;
        $this->dataProvider->criteria->with = array(
            'brand:resetScope',
            'category:resetScope',
            'material',
            'unit',
            'saleDetails' => array(
                'with' => array(
                    'saleHeader' => array(
                        'with' => array(
                            'customer'
                        ),
                    ),
                ),
            ),
        );
    }

    public function setupPaging($pageSize, $currentPage) {
        $pageSize = (empty($pageSize)) ? 10 : $pageSize;
        $pageSize = ($pageSize <= 0) ? 1 : $pageSize;
        $this->dataProvider->pagination->pageSize = $pageSize;

        $currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
        $this->dataProvider->pagination->currentPage = $currentPage;
    }

    public function setupSorting() {
        $this->dataProvider->sort->attributes = array('name', 'category_id');
        $this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
    }

    public function setupFilter($startDate, $endDate, $category, $branch, $customerCompany) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

        $this->dataProvider->criteria->addBetweenCondition('saleHeader.date', $startDate, $endDate);
        $this->dataProvider->criteria->compare('t.category_id', $category);
        $this->dataProvider->criteria->compare('t.is_inactive', 0);
        $this->dataProvider->criteria->compare('saleHeader.branch_id', $branch);
        $this->dataProvider->criteria->compare('saleHeader.is_inactive', 0);
        $this->dataProvider->criteria->compare('customer.company', $customerCompany, true);
    }

    public function getGrandTotal() {
        $grandTotal = 0.00;

        foreach ($this->dataProvider->data as $data)
            $grandTotal += $data->grandTotal;

        return $grandTotal;
    }

}
