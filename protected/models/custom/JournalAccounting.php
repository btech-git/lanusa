<?php

class JournalAccounting extends JournalAccountingBase {

    public $currentSaldo;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

//    public static function getLedgerBeginningBalances($coaIds, $startDate, $branchId) {
//        $inIdsSql = 'NULL';
//        if (!empty($coaIds)) {
//            $inIdsSql = implode(',', $coaIds);
//        }
//        
//        $branchConditionSql = '';
//        
//        $params = array(
//            ':start_date' => $startDate,
//        );
//        
//        if (!empty($branchId)) {
//            $branchConditionSql = ' AND j.branch_id = :branch_id';
//            $params[':branch_id'] = $branchId;
//        }
//        
//        $sql = "
//            SELECT j.coa_id, IF(a.normal_balance = 'Debit', COALESCE(SUM(j.amount), 0), COALESCE(SUM(j.amount), 0) * -1) AS beginning_balance 
//            FROM (
//                SELECT coa_id, tanggal_transaksi, total AS amount, branch_id
//                FROM " . JurnalUmum::model()->tableName() . "
//                WHERE debet_kredit = 'D' AND is_coa_category = 0 AND tanggal_transaksi >= '" . AppParam::BEGINNING_TRANSACTION_DATE . "'
//                UNION ALL
//                SELECT coa_id, tanggal_transaksi, total * -1 AS amount, branch_id
//                FROM " . JurnalUmum::model()->tableName() . "
//                WHERE debet_kredit = 'K' AND is_coa_category = 0 AND tanggal_transaksi >= '" . AppParam::BEGINNING_TRANSACTION_DATE . "'
//            ) j
//            INNER JOIN " . Coa::model()->tableName() . " a ON a.id = j.coa_id
//            WHERE j.coa_id IN ({$inIdsSql}) AND j.tanggal_transaksi < :start_date" . $branchConditionSql . " 
//            GROUP BY j.coa_id
//        ";
//
//        $resultSet = Yii::app()->db->createCommand($sql)->queryAll(true, $params);
//
//        return $resultSet;
//    }
    
    public static function getGeneralLedgerReport($coaIds, $startDate, $endDate, $branchId) {
        $inIdsSql = 'NULL';
        if (!empty($coaIds)) {
            $inIdsSql = implode(',', $coaIds);
        }
        
        $branchConditionSql = '';
        
        $params = array(
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        );
        
        if (!empty($branchId)) {
            $branchConditionSql = ' AND branch_id = :branch_id';
            $params[':branch_id'] = $branchId;
        }
        
        $sql = "
            SELECT account_id, transaction_number, date, memo, type, debit, credit
            FROM " . JournalAccounting::model()->tableName() . " 
            WHERE account_id IN ({$inIdsSql}) AND date BETWEEN :start_date AND :end_date AND is_inactive = 0" . $branchConditionSql . "
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