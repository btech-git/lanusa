<?php

/**
 * @property integer $id
 * @property string $debit
 * @property string $credit
 * @property string $memo
 * @property integer $journal_voucher_header_id
 * @property integer $account_id
 * @property integer $is_inactive
 *
 * @property JournalVoucherHeader $journalVoucherHeader
 * @property Account $account
 */
class JournalVoucherDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_journal_voucher_detail';
    }

    public function rules() {
        return array(
            array('journal_voucher_header_id, account_id', 'required'),
            array('journal_voucher_header_id, account_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('debit, credit', 'length', 'max' => 18),
            array('memo', 'length', 'max' => 200),
            // The following rule is used by search().
            array('id, debit, credit, memo, journal_voucher_header_id, account_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'journalVoucherHeader' => array(self::BELONGS_TO, 'JournalVoucherHeader', 'journal_voucher_header_id'),
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'memo' => 'Memo',
            'journal_voucher_header_id' => 'Journal Voucher Header',
            'account_id' => 'Account',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.debit', $this->debit, true);
        $criteria->compare('t.credit', $this->credit, true);
        $criteria->compare('t.memo', $this->memo, true);
        $criteria->compare('t.journal_voucher_header_id', $this->journal_voucher_header_id);
        $criteria->compare('t.account_id', $this->account_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
