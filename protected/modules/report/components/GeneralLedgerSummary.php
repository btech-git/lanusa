<?php

class GeneralLedgerSummary extends CComponent {

    public $dataProvider;

    public function __construct($dataProvider) {
        $this->dataProvider = $dataProvider;
    }

    public function setupLoading() {
//        $this->dataProvider->criteria->with = array(
//            'journalAccountings',
//        );
        $this->dataProvider->criteria->together = true;
        
    }

    public function setupPaging($pageSize, $currentPage) {
        $pageSize = (empty($pageSize)) ? 1000 : $pageSize;
        $pageSize = ($pageSize <= 0) ? 1 : $pageSize;
        $this->dataProvider->pagination->pageSize = $pageSize;

        $currentPage = (empty($currentPage)) ? 0 : $currentPage - 1;
        $this->dataProvider->pagination->currentPage = $currentPage;
    }

    public function setupSorting() {
        $this->dataProvider->sort->defaultOrder = 't.code ASC';
        $this->dataProvider->criteria->order = $this->dataProvider->sort->orderBy;
    }

    public function setupFilter($accountIdList, $startDate, $endDate) {
        $inIdsSql = 'NULL';
        if (!empty($accountIdList)) {
            $inIdsSql = implode(',', $accountIdList);
        }
        
//        $this->dataProvider->criteria->addBetweenCondition('journalAccountings.date', $startDate, $endDate);
        $this->dataProvider->criteria->addCondition("t.id IN ({$inIdsSql})");
        $this->dataProvider->criteria->compare('t.is_inactive', 0);
    }
}