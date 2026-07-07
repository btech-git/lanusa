<?php

class Deposit extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $depositHeader = DepositHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($depositHeader !== null)
            $this->header->setCodeNumber($depositHeader->cn_ordinal, $depositHeader->cn_month, $depositHeader->cn_year, $depositHeader->branch_id);

        $this->header->setCodeNumberByNext($currentMonth, $currentYear);
    }

    public function addDetail($id) {
        $account = Account::model()->findByPk($id);

        $exist = false;
        foreach ($this->details as $i => $detail) {
            if ($account->id === $detail->account_id) {
                $exist = true;
                break;
            }
        }

        if ($exist)
            $this->details[$i]->amount++;
        else {
            $detail = new DepositDetail();
            $detail->account_id = $account->id;
            $this->details[] = $detail;
        }
    }

    public function removeDetailAt($index) {
        array_splice($this->details, $index, 1);
    }

    public function validate() {
        $valid = $this->header->validate();

        $valid = $this->validateDetailsCount() && $valid;

        if (count($this->details) > 0) {
            foreach ($this->details as $detail) {
                $fields = array('amount', 'memo');
                $valid = $detail->validate($fields) && $valid;
            }
        } else
            $valid = false;

        return $valid;
    }

    public function validateDetailsCount() {
        $valid = true;
        if (count($this->details) === 0) {
            $valid = false;
            $this->header->addError('error', 'Form tidak ada data untuk insert database. Minimal satu data detail untuk melakukan penyimpanan.');
        }

        return $valid;
    }

    public function flush() {
        $valid = $this->header->save(false);

        if ($this->header->is_bank) {
            $depositHeaderConstant = DepositHeader::CN_CONSTANT_BANK;
        } else {
            $depositHeaderConstant = DepositHeader::CN_CONSTANT_CASH;
        }

        JournalAccounting::model()->deleteAllByAttributes(array(
            'transaction_number' => $this->header->getCodeNumber($depositHeaderConstant),
            'branch_id' => $this->header->branch_id,
            'type' => 1,
        ));

        foreach ($this->details as $detail) {
            if ($detail->amount <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->deposit_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;
            if ($detail->is_inactive == 0) {
                $accountingJournalCredit = AccountingJournalHelper::make(
                                'credit',
                                $this->header->getCodeNumber($depositHeaderConstant),
                                $this->header->date,
                                $detail->account_id,
                                $this->header->branch_id,
                                $detail->amount,
                                1,
                                $detail->memo
                );
                $valid = $accountingJournalCredit->save() && $valid;

                $accountingJournalDebit = AccountingJournalHelper::make(
                                'debit',
                                $this->header->getCodeNumber($depositHeaderConstant),
                                $this->header->date,
                                $this->header->account_id,
                                $this->header->branch_id,
                                $detail->amount,
                                1,
                                $this->header->note
                );
                $valid = $accountingJournalDebit->save() && $valid;
            }
        }

        return $valid;
    }

    public function save($dbConnection) {
        $dbTransaction = $dbConnection->beginTransaction();
        try {
            $valid = $this->validate() && IdempotentManager::build()->save() && $this->flush();
            if ($valid)
                $dbTransaction->commit();
            else
                $dbTransaction->rollback();
        } catch (Exception $e) {
            $dbTransaction->rollback();
            $valid = false;
        }

        return $valid;
    }

    public function getTotal() {
        $total = 0.00;

        foreach ($this->details as $detail) {
            if ($detail->is_inactive == 0)
                $total += $detail->amount;
        }

        return $total;
    }
}
