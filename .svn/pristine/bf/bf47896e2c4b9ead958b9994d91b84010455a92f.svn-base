<?php

/**
 * @property integer $id
 * @property string $amount
 * @property string $memo
 * @property integer $deposit_header_id
 * @property integer $account_id
 * @property integer $is_inactive
 *
 * @property DepositHeader $depositHeader
 * @property Account $account
 */
class DepositDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_deposit_detail';
    }

    public function rules() {
        return array(
            array('deposit_header_id, account_id', 'required'),
            array('deposit_header_id, account_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('amount', 'length', 'max' => 18),
            array('memo', 'length', 'max' => 200),
            // The following rule is used by search().
            array('id, amount, memo, deposit_header_id, account_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'depositHeader' => array(self::BELONGS_TO, 'DepositHeader', 'deposit_header_id'),
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'amount' => 'Amount',
            'memo' => 'Memo',
            'deposit_header_id' => 'Deposit Header',
            'account_id' => 'Account',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.memo', $this->memo, true);
        $criteria->compare('t.deposit_header_id', $this->deposit_header_id);
        $criteria->compare('t.account_id', $this->account_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
