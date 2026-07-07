<?php

class CustomerReceivableSummary extends CComponent {

    public $dataProvider;
    public $details;

    public function __construct($dataProvider, $details) {
        $this->dataProvider = $dataProvider;
        $this->details = $details;
    }

    public function setupLoading($startDate, $endDate, $startAccount, $endAccount) {
        $this->dataProvider->criteria->with = array(
            'journalAccountings' => array(
                'condition' => "journalAccountings.date BETWEEN :startDate AND :endDate",
                'params' => array(':startDate' => $startDate, ':endDate' => $endDate),
            ),
            'branch:resetScope',
        );

        $this->dataProvider->criteria->addCondition('account_category_id = 3');

        if ($startAccount != null && $endAccount != null) {
            $this->dataProvider->criteria->with = array(
                'journalAccountings' => array(
                    'condition' => "journalAccountings.account_id BETWEEN :startAccount AND :endAccount",
                    'params' => array(':startAccount' => $startAccount, ':endAccount' => $endAccount),
                ),
                'branch:resetScope',
            );
        }

        $this->dataProvider->criteria->compare('t.is_inactive', 0);
    }

    public function setupPaging($pageSize, $currentPage) {
        $pageSize = (empty($pageSize)) ? 10 : $pageSize;
        $pageSize = ($pageSize <= 0) ? 1 : $pageSize;
        $this->dataProvider->pagination->pageSize = $pageSize;

        $currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
        $this->dataProvider->pagination->currentPage = $currentPage;
    }

//	public function setupSorting()
//	{
//		$this->dataProvider->sort->attributes = array('t.date', 'branch_id');
//		$this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
//	}

    public function setupFilter($startDate, $endDate, $branchId, $startAccount, $endAccount) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;
        $this->details->criteria->addBetweenCondition('t.date', $startDate, $endDate);
        $this->dataProvider->criteria->addBetweenCondition('t.id', $startAccount, $endAccount);
        $this->dataProvider->criteria->compare('t.branch_id', $branchId);
    }

    public function reportGrandTotal($endDate) {
        $grandTotal = 0.00;

        foreach ($this->dataProvider->data as $data)
            $grandTotal += $data->getEndBalanceLedger($data->id, $endDate);

        return $grandTotal;
    }

}
