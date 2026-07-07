<?php

/**
 * @property integer $id
 * @property string $amount
 * @property string $memo
 * @property integer $expense_header_id
 * @property integer $account_id
 * @property integer $is_inactive
 *
 * @property ExpenseHeader $expenseHeader
 * @property Account $account
 */
class ExpenseDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_expense_detail';
    }

    public function rules() {
        return array(
            array('expense_header_id, account_id', 'required'),
            array('expense_header_id, account_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('amount', 'length', 'max' => 18),
            array('memo', 'length', 'max' => 200),
            // The following rule is used by search().
            array('id, amount, memo, expense_header_id, account_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'expenseHeader' => array(self::BELONGS_TO, 'ExpenseHeader', 'expense_header_id'),
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'amount' => 'Amount',
            'memo' => 'Memo',
            'expense_header_id' => 'Expense Header',
            'account_id' => 'Account',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.memo', $this->memo, true);
        $criteria->compare('t.expense_header_id', $this->expense_header_id);
        $criteria->compare('t.account_id', $this->account_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
