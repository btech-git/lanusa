<?php

/**
 * @property integer $id
 * @property string $amount
 * @property string $memo
 * @property integer $purchase_payment_header_id
 * @property integer $account_id
 * @property integer $payment_type_id
 * @property integer $is_inactive
 *
 * @property PurchasePaymentHeader $purchasePaymentHeader
 * @property PaymentType $paymentType
 * @property Account $account
 */
class PurchasePaymentDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_purchase_payment_detail';
    }

    public function rules() {
        return array(
            array('purchase_payment_header_id, account_id, payment_type_id', 'required'),
            array('purchase_payment_header_id, account_id, payment_type_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('amount', 'length', 'max' => 18),
            array('memo', 'length', 'max' => 200),
            // The following rule is used by search().
            array('id, amount, memo, purchase_payment_header_id, account_id, payment_type_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'purchasePaymentHeader' => array(self::BELONGS_TO, 'PurchasePaymentHeader', 'purchase_payment_header_id'),
            'paymentType' => array(self::BELONGS_TO, 'PaymentType', 'payment_type_id'),
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'amount' => 'Amount',
            'memo' => 'Memo',
            'purchase_payment_header_id' => 'Purchase Payment Header',
            'account_id' => 'Account',
            'payment_type_id' => 'Payment Type',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.memo', $this->memo, true);
        $criteria->compare('t.purchase_payment_header_id', $this->purchase_payment_header_id);
        $criteria->compare('t.account_id', $this->account_id);
        $criteria->compare('t.payment_type_id', $this->payment_type_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
