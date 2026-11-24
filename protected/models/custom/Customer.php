<?php

class Customer extends CustomerBase {

    //attribute for filter
    public $branchName;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getTotalSales() {
        $totalSales = 0.00;

        foreach ($this->saleHeaders as $saleHeader) {
            $totalSales += $saleHeader->grandTotal;
        }

        return $totalSales;
    }

    public function customerBySaleCustomerSearch($customerSelected, $branch, $startDate, $endDate) {

        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

        $criteria = new CDbCriteria();

        if ($customerSelected) {
            $criteria->condition = 't.id IN (
                SELECT customer_id 
                FROM tblla_sale_header 
                WHERE customer_id = :customerId AND branch_id = :branchId AND date BETWEEN :startDate AND :endDate
            )';
            
            $criteria->params = array(
                ':customerId' => $customerSelected->id,
                ':branchId' => $branch,
                ':startDate' => $startDate,
                ':endDate' => $endDate,
            );
        } else {
            $criteria->condition = 't.id IN (
                SELECT customer_id 
                FROM tblla_sale_header 
                WHERE branch_id = :branchId AND date BETWEEN :startDate AND :endDate
            )';
            $criteria->params = array(
                'branchId' => $branch,
                ':startDate' => $startDate,
                ':endDate' => $endDate,
            );
        }

        return new CActiveDataProvider(new Customer(), array(
            'criteria' => $criteria
        ));
    }
    
    public function getRemainingCreditLimit() {
        $totalReceivable = '0.00';
        
        $saleReceiptHeaders = SaleReceiptHeader::model()->findAll(array(
            'condition' => 't.customer_id = :customer_id AND t.grand_total - t.total_payment > 0', 
            'params' => array(':customer_id' => $this->id),
        ));
        
        foreach ($saleReceiptHeaders as $saleReceiptHeader) {
            if ($saleReceiptHeader->is_inactive == 0) {
                $totalReceivable += $saleReceiptHeader->grand_total + $saleReceiptHeader->total_payment;
            }
        }
        
        return $this->credit_limit - $totalReceivable;
    }
}