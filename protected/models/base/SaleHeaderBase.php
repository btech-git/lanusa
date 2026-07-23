<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property integer $tax
 * @property string $discount
 * @property string $shipping_fee
 * @property string $driver
 * @property string $plate_number
 * @property string $note
 * @property string $reference
 * @property integer $customer_id
 * @property integer $sale_downpayment_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_non_tax
 * @property integer $is_inactive
 * @property integer $employee_id_salesman
 *
 * @property DeliveryHeader[] $deliveryHeaders
 * @property SaleDetail[] $saleDetails
 * @property Customer $customer
 * @property Admin $admin
 * @property SaleDownpayment $saleDownpayment
 * @property Branch $branch
 * @property EmployeeIdSalesman $employeeIdSalesman
 */
class SaleHeaderBase extends MonthlyTransactionActiveRecord {

    //customer attribute
    public $customerCompany;

    public function tableName() {
        return 'tblla_sale_header';
    }

    public function rules() {
        return array(
            array('cn_ordinal, cn_month, cn_year, date, customer_id, branch_id, admin_id', 'required'),
            array('cn_ordinal, cn_month, cn_year, tax, customer_id, sale_downpayment_id, branch_id, admin_id, is_non_tax, is_inactive, employee_id_salesman', 'numerical', 'integerOnly' => true),
            array('discount', 'length', 'max' => 10),
            array('shipping_fee', 'length', 'max' => 18),
            array('driver, plate_number', 'length', 'max' => 60),
            array('reference', 'length', 'max' => 100),
            array('note', 'safe'),
            // The following rule is used by search().
            array('id, cn_ordinal, cn_month, cn_year, date, tax, discount, shipping_fee, driver, plate_number, note, reference, customer_id, sale_downpayment_id, branch_id, admin_id, is_non_tax, is_inactive, employee_id_salesman', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'deliveryHeaders' => array(self::HAS_MANY, 'DeliveryHeader', 'sale_header_id'),
            'saleDetails' => array(self::HAS_MANY, 'SaleDetail', 'sale_header_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            'saleDownpayment' => array(self::BELONGS_TO, 'SaleDownpayment', 'sale_downpayment_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'employeeIdSalesman' => array(self::BELONGS_TO, 'Employee', 'employee_id_salesman'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cn_ordinal' => 'Cn Ordinal',
            'cn_month' => 'Cn Month',
            'cn_year' => 'Cn Year',
            'date' => 'Date',
            'tax' => 'Tax',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'driver' => 'Driver',
            'plate_number' => 'Plate Number',
            'note' => 'Note',
            'reference' => 'Reference',
            'customer_id' => 'Customer',
            'sale_downpayment_id' => 'Downpayment',
            'branch_id' => 'Branch',
            'admin_id' => 'Admin',
            'is_non_tax' => 'Is Non Tax',
            'is_inactive' => 'Is Inactive',
            'employee_id_salesman' => 'Salesman'
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.tax', $this->tax);
        $criteria->compare('t.discount', $this->discount, true);
        $criteria->compare('t.shipping_fee', $this->shipping_fee, true);
        $criteria->compare('t.driver', $this->driver, true);
        $criteria->compare('t.plate_number', $this->plate_number, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.reference', $this->reference, true);
        $criteria->compare('t.customer_id', $this->customer_id);
        $criteria->compare('t.sale_downpayment_id', $this->sale_downpayment_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this->resetScope(), array(
            'criteria' => $criteria,
        ));
    }
}