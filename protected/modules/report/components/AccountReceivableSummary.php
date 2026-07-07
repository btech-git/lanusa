<?php

class AccountReceivableSummary extends CComponent {

    public $dataProvider;
    public $details;

    public function __construct($dataProvider, $details) {
        $this->dataProvider = $dataProvider;
        $this->details = $details;
    }

    public function setupLoading($startDate, $endDate) {
        $this->dataProvider->criteria->with = array(
            'journalAccountings' => array(
                'condition' => "journalAccountings.date BETWEEN :startDate AND :endDate",
                'params' => array(':startDate' => $startDate, ':endDate' => $endDate),
            ),
            'branch:resetScope',
        );

        $this->dataProvider->criteria->compare('t.is_inactive', 0);
    }

    public function setupPaging($pageSize, $currentPage) {
        $pageSize = (empty($pageSize)) ? 10 : $pageSize;
        $pageSize = ($pageSize <= 0) ? 1 : $pageSize;
        $this->dataProvider->pagination->pageSize = $pageSize;

        $currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
        $this->dataProvider->pagination->currentPage = $currentPage;
    }

    public function setupSorting() {
        $this->dataProvider->sort->attributes = array('t.date', 'branch_id');
        $this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
    }

    public function setupFilter($startDate, $endDate, $branchId) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
        //$this->dataProvider->criteria->with = array('journalAccountings');
        $this->details->criteria->join = "INNER JOIN tblla_account a ON (t.account_id = a.id)";
        $this->details->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        $this->dataProvider->criteria->compare('t.branch_id', $branchId);
    }

    public function getSaldo() {
        $saldo = 0.00;

        foreach ($this->dataProvider->data as $data) {
            $saldo = $data->accountBalance;
            foreach ($data->journalAccountings as $details) {
                $saldo = $saldo + $details->debit - $details->credit;
                $details->currentSaldo = $saldo;
            }
        }

        return $saldo;
    }
}
