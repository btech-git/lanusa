<?php

class JournalVoucher extends CComponent {

    public $header;
    public $details;

    public function __construct($header, array $details) {
        $this->header = $header;
        $this->details = $details;
    }

    public function generateCodeNumber($branchId, $currentMonth, $currentYear) {
        $journalVoucherHeader = JournalVoucherHeader::model()->find(array(
            'order' => 'cn_month DESC, cn_ordinal DESC',
            'condition' => 'branch_id = :branch_id AND cn_year = :cn_year AND cn_month = :cn_month',
            'params' => array(':branch_id' => $branchId, ':cn_year' => $currentYear, ':cn_month' => $currentMonth),
        ));

        if ($journalVoucherHeader !== null)
            $this->header->setCodeNumber($journalVoucherHeader->cn_ordinal, $journalVoucherHeader->cn_month, $journalVoucherHeader->cn_year, $journalVoucherHeader->branch_id);

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
            $this->details[$i]->debit++;
        else {
            $detail = new JournalVoucherDetail();
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
                $fields = array('debit', 'credit', 'account_id');
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

        JournalAccounting::model()->deleteAllByAttributes(array(
            'transaction_number' => $this->header->getCodeNumber(JournalVoucherHeader::CN_CONSTANT),
//			'account_id' => $detail->account_id, 
            'branch_id' => $this->header->branch_id,
            'type' => 7,
        ));

        foreach ($this->details as $detail) {
            if ($detail->debit <= 0 && $detail->credit <= 0)
                continue;

            if ($detail->isNewRecord)
                $detail->journal_voucher_header_id = $this->header->id;

            $valid = $detail->save(false) && $valid;

            if ((int) $detail->is_inactive === 0) {
                $accountingJournal = new JournalAccounting();
                $accountingJournal->transaction_number = $this->header->getCodeNumber(JournalVoucherHeader::CN_CONSTANT);
                $accountingJournal->account_id = $detail->account_id;
                $accountingJournal->type = 7;
                $accountingJournal->credit = $detail->credit;
                $accountingJournal->debit = $detail->debit;
                $accountingJournal->date = $this->header->date;
                $accountingJournal->admin_id = Yii::app()->user->id;
                $accountingJournal->branch_id = $this->header->branch_id;
                $accountingJournal->memo = $detail->memo;

                $valid = $accountingJournal->save() && $valid;
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

    public function getTotalDebit() {
        $total = 0.00;
        foreach ($this->details as $detail)
            $total += $detail->debit;

        return $total;
    }

    public function getTotalCredit() {
        $total = 0.00;
        foreach ($this->details as $detail)
            $total += $detail->credit;

        return $total;
    }
}
