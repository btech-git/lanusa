<?php

/**
 * @property integer $id
 * @property string $quantity
 * @property string $unit_price
 * @property string $discount
 * @property integer $purchase_header_id
 * @property integer $product_id
 * @property integer $is_inactive
 *
 * @property PurchaseHeader $purchaseHeader
 * @property Product $product
 * @property ReceiveDetail[] $receiveDetails
 */
class PurchaseDetailBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_purchase_detail';
    }

    public function rules() {
        return array(
            array('purchase_header_id, product_id', 'required'),
            array('purchase_header_id, product_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('quantity, discount', 'length', 'max' => 10),
            array('unit_price', 'length', 'max' => 18),
            // The following rule is used by search().
            array('id, quantity, unit_price, discount, purchase_header_id, product_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'purchaseHeader' => array(self::BELONGS_TO, 'PurchaseHeader', 'purchase_header_id'),
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'receiveDetails' => array(self::HAS_MANY, 'ReceiveDetail', 'purchase_detail_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'discount' => 'Discount',
            'purchase_header_id' => 'Purchase Header',
            'product_id' => 'Product',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.quantity', $this->quantity, true);
        $criteria->compare('t.unit_price', $this->unit_price, true);
        $criteria->compare('t.discount', $this->discount, true);
        $criteria->compare('t.purchase_header_id', $this->purchase_header_id);
        $criteria->compare('t.product_id', $this->product_id);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

}
