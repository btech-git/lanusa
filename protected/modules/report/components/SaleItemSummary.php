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
        );
    }

    public function setupPaging($pageSize, $currentPage) {
        $pageSize = (empty($pageSize)) ? 100 : $pageSize;
        $pageSize = ($pageSize <= 0) ? 1 : $pageSize;
        $this->dataProvider->pagination->pageSize = $pageSize;

        $currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
        $this->dataProvider->pagination->currentPage = $currentPage;
    }

    public function setupSorting() {
//        $this->dataProvider->sort->attributes = array('name', 'category_id');
        $this->dataProvider->criteria->order = 't.name ASC, t.size ASC';
    }

    public function setupFilter($startDate, $endDate, $categoryId, $branchId, $customerCompany) {

        $this->dataProvider->criteria->addCondition("EXISTS (
                SELECT d.product_id
                FROM " . SaleDetail::model()->tableName() . " d
                INNER JOIN " . SaleHeader::model()->tableName() . " h ON h.id = d.sale_header_id
                INNER JOIN " . Customer::model()->tableName() . " c ON c.id = h.customer_id
                WHERE d.product_id = t.id AND h.date BETWEEN :start_date AND :end_date AND c.company LIKE :customer_company AND h.branch_id = :branch_id
                AND d.is_inactive = 0 AND h.is_inactive = 0
        )");
        $this->dataProvider->criteria->params[':start_date'] = $startDate;
        $this->dataProvider->criteria->params[':end_date'] = $endDate;
        $this->dataProvider->criteria->params[':branch_id'] = $branchId;
        $this->dataProvider->criteria->params[':customer_company'] = "%{$customerCompany}%";
        
        $this->dataProvider->criteria->compare('t.category_id', $categoryId);
        $this->dataProvider->criteria->compare('t.is_inactive', 0);
    }

    public function getGrandTotal() {
        $grandTotal = 0.00;

        foreach ($this->dataProvider->data as $data) {
            $grandTotal += $data->grandTotal;
        }

        return $grandTotal;
    }
}