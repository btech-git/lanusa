<?php

class JournalAccounting extends JournalAccountingBase {

    public $currentSaldo;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getLedgerBeginningBalances($coaIds, $startDate) {
        $inIdsSql = 'NULL';
        if (!empty($coaIds)) {
            $inIdsSql = implode(',', $coaIds);
        }
        
        $params = array(
            ':start_date' => $startDate,
        );
        
        $sql = "
            SELECT j.account_id, COALESCE(SUM(j.debit - j.credit), 0) AS beginning_balance 
            FROM " . JournalAccounting::model()->tableName() . " j
            WHERE j.account_id IN ({$inIdsSql}) AND j.date < :start_date 
            GROUP BY j.account_id
        ";

        $resultSet = Yii::app()->db->createCommand($sql)->queryAll(true, $params);

        return $resultSet;
    }
    
    public static function getGeneralLedgerReport($coaIds, $startDate, $endDate) {
        $inIdsSql = 'NULL';
        if (!empty($coaIds)) {
            $inIdsSql = implode(',', $coaIds);
        }
        
        $params = array(
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        );
        
        $sql = "
            SELECT account_id, transaction_number, date, memo, type, debit, credit
            FROM " . JournalAccounting::model()->tableName() . " 
            WHERE account_id IN ({$inIdsSql}) AND date BETWEEN :start_date AND :end_date AND is_inactive = 0
            ORDER BY account_id ASC, date ASC, transaction_number ASC
        ";

        $resultSet = Yii::app()->db->createCommand($sql)->queryAll(true, $params);
        
        return $resultSet;
    }
    
    public static function getTransactionJournalReport($startDate, $endDate, $transactionType, $branchId, $coaId, $currentPage, $pageSize) {
        
        $pageOffset = ($currentPage - 1) * $pageSize;
        $transactionTypeConditionSql = '';
        $branchConditionSql = '';
        $coaConditionSql = '';
        
        $params = array(
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        );
        
        if (!empty($transactionType)) {
            $transactionTypeConditionSql = ' AND type = :type';
            $params[':type'] = $transactionType;
        }
        
        if (!empty($branchId)) {
            $branchConditionSql = ' AND branch_id = :branch_id';
            $params[':branch_id'] = $branchId;
        }
        
        if (!empty($coaId)) {
            $coaConditionSql = ' AND account_id = :account_id';
            $params[':account_id'] = $coaId;
        }
        
        $sql = "SELECT transaction_number, MIN(date) AS transaction_date, MIN(memo) AS transaction_subject
                FROM " . JournalAccounting::model()->tableName() . "
                WHERE date BETWEEN :start_date AND :end_date" . $transactionTypeConditionSql . $branchConditionSql . $coaConditionSql ."
                GROUP BY transaction_number
                ORDER BY transaction_date ASC
                LIMIT {$pageOffset}, {$pageSize}";
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll(true, $params);

        return $resultSet;
    }
    
    public static function getTransactionJournalCount($startDate, $endDate, $transactionType, $branchId, $coaId) {
        
        $transactionTypeConditionSql = '';
        $branchConditionSql = '';
        $coaConditionSql = '';
        
        $params = array(
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        );
        
        if (!empty($transactionType)) {
            $transactionTypeConditionSql = ' AND type = :type';
            $params[':type'] = $transactionType;
        }
        
        if (!empty($branchId)) {
            $branchConditionSql = ' AND branch_id = :branch_id';
            $params[':branch_id'] = $branchId;
        }
        
        if (!empty($coaId)) {
            $coaConditionSql = ' AND account_id = :account_id';
            $params[':account_id'] = $coaId;
        }
        
        $sql = "SELECT COUNT(DISTINCT transaction_number) AS transaction_item_count
                FROM " . JournalAccounting::model()->tableName() . "
                WHERE date BETWEEN :start_date AND :end_date" . $transactionTypeConditionSql . $branchConditionSql . $coaConditionSql;
        
        $count = Yii::app()->db->createCommand($sql)->queryScalar($params);

        return $count;
    }

}