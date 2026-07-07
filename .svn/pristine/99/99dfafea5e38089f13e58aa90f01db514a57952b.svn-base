<?php

class SaleInvoiceTransaction extends CComponent {

    public $header;

    public function __construct($header) {
        $this->header = $header;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
//		$invoice = SaleInvoice::model()->find(array(
//			'order' => 'id DESC',
//			'condition' => 'branch_id = :branch_id',
//			'params' => array(':branch_id' => $branchId),
//		));
//		
//		if ($invoice !== null)
//			$this->header->setCodeNumber($this->header->deliveryHeader->cn_ordinal, $this->header->deliveryHeader->cn_month, $this->header->deliveryHeader->cn_year, $invoice->branch_id);
//		
//		$this->header->setCodeNumberByNext($currentMonth, $currentYear);

        $this->header->setCodeNumber($this->header->deliveryHeader->cn_ordinal, $this->header->deliveryHeader->cn_month, $this->header->deliveryHeader->cn_year, $branchId);
    }

    public function save($dbConnection) {
        $dbTransaction = $dbConnection->beginTransaction();
        try {
            $valid = $this->validate() && IdempotentManager::build()->save() && $this->flush();
            if ($valid) {
                $dbTransaction->commit();
            } else {
                $dbTransaction->rollback();
            }
        } catch (Exception $e) {
            $dbTransaction->rollback();
            $valid = false;
        }

        return $valid;
    }

    public function validate() {
        $this->header->tax_percentage = $this->header->deliveryHeader->saleHeader->tax;
        $valid = $this->header->validate();

        return $valid;
    }

    public function flush() {
        $valid = $this->header->save(false);

        JournalAccounting::model()->deleteAllByAttributes(array(
            'transaction_number' => $this->header->getCodeNumber(SaleInvoice::CN_CONSTANT),
            'branch_id' => $this->header->branch_id,
            'type' => 3,
        ));

        if ((int)$this->header->is_inactive === 0) {
            $accountingJournalDebit = AccountingJournalHelper::make(
                'debit', 
                $this->header->getCodeNumber(SaleInvoice::CN_CONSTANT), 
                $this->header->date, 
                $this->header->deliveryHeader->saleHeader->customer->account_id, 
                $this->header->branch_id, 
                $this->header->grandTotal, 
                3, 
                $this->header->note
            );
            $valid = $accountingJournalDebit->save() && $valid;

            if ($this->header->discount > 0.00) {
                $accountingJournalDebit = AccountingJournalHelper::make(
                    'debit', 
                    $this->header->getCodeNumber(SaleInvoice::CN_CONSTANT), 
                    $this->header->date, 
                    $this->getDiscountCode($this->header->branch_id), 
                    $this->header->branch_id, 
                    $this->header->discount, 
                    3, 
                    $this->header->note
                );
                $valid = $accountingJournalDebit->save() && $valid;
            }

            $accountingJournalCredit = AccountingJournalHelper::make(
                'credit', 
                $this->header->getCodeNumber(SaleInvoice::CN_CONSTANT), 
                $this->header->date, 
                $this->getCode($this->header->branch_id), 
                $this->header->branch_id, 
                $this->header->deliveryHeader->subTotal, 
                3, 
                $this->header->note
            );
            $valid = $accountingJournalCredit->save() && $valid;

            if ($this->header->calculatedTax > 0.00) {
                $accountingJournalCredit = AccountingJournalHelper::make(
                    'credit', 
                    $this->header->getCodeNumber(SaleInvoice::CN_CONSTANT), 
                    $this->header->date, 
                    $this->getTaxCode($this->header->branch_id), 
                    $this->header->branch_id, 
                    $this->header->calculatedTax, 
                    3, 
                    $this->header->note
                );
                $valid = $accountingJournalCredit->save() && $valid;
            }

            if ($this->header->shipping_fee > 0.00) {
                $accountingJournalCredit = AccountingJournalHelper::make(
                    'credit', 
                    $this->header->getCodeNumber(SaleInvoice::CN_CONSTANT), 
                    $this->header->date, 
                    $this->getShippingCode($this->header->branch_id), 
                    $this->header->branch_id, 
                    $this->header->shipping_fee, 
                    3, 
                    $this->header->note
                );
                $valid = $accountingJournalCredit->save() && $valid;
            }
        }

        return $valid;
    }

    public function getCode($branchId) {
        $code = 0;
        if ($branchId == 1)
            $code = 270;
        else if ($branchId == 2)
            $code = 784;
        else if ($branchId == 3)
            $code = 1058;
        else if ($branchId == 4)
            $code = 1986;

        return $code;
    }

    public function getDiscountCode($branchId) {
        $code = 0;
        if ($branchId == 1)
            $code = 272;
        else if ($branchId == 2)
            $code = 786;
        else if ($branchId == 3)
            $code = 1060;
        else if ($branchId == 4)
            $code = 1988;

        return $code;
    }

    public function getTaxCode($branchId) {
        $code = 0;
        if ($branchId == 1)
            $code = 120;
        else if ($branchId == 2)
            $code = 978;
        else if ($branchId == 3)
            $code = 598;
        else if ($branchId == 4)
            $code = 1802;

        return $code;
    }

    public function getShippingCode($branchId) {
        $code = 0;
        if ($branchId == 1)
            $code = 318;
        else if ($branchId == 2)
            $code = 835;
        else if ($branchId == 3)
            $code = 1108;
        else if ($branchId == 4)
            $code = 2034;

        return $code;
    }

    public function getCalculatedTax() {
        $subTotal = ($this->header->deliveryHeader === null) ? 0.00 : $this->header->deliveryHeader->subTotal;
        $tax = ($this->header->deliveryHeader === null) ? 0.00 : 10;

        return ($subTotal - $this->header->discount + $this->header->shipping_fee) * $tax / 100;
    }

    public function getGrandTotal() {
        $subTotal = ($this->header->deliveryHeader === null) ? 0.00 : $this->header->deliveryHeader->subTotal;

        return $subTotal - $this->header->discount + $this->header->shipping_fee + $this->header->calculatedTax;
    }

}