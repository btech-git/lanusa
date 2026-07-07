<?php

/**
 * @property integer $id
 * @property integer $quantity
 * @property integer $transfer_header_id
 * @property integer $product_id
 * @property integer $is_inactive
 *
 * @property Product $product
 * @property TransferHeader $transferHeader
 */
class TransferDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_transfer_detail';
    }

    public function rules() {
        return array(
            array('transfer_header_id, product_id', 'required'),
            array('quantity, transfer_header_id, product_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, quantity, transfer_header_id, product_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'transferHeader' => array(self::BELONGS_TO, 'TransferHeader', 'transfer_header_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'quantity' => 'Quantity',
            'transfer_header_id' => 'Transfer Header',
            'product_id' => 'Product',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('transfer_header_id', $this->transfer_header_id);
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}