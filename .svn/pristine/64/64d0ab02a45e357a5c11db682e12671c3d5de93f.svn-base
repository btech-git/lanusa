<?php

/**
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $note
 * @property integer $branch_id
 * @property integer $is_inactive
 *
 * @property AdjustmentHeader[] $adjustmentHeaders
 * @property Branch $branch
 * @property DeliveryHeader[] $deliveryHeaders
 * @property DepositHeader[] $depositHeaders
 * @property ExpenseHeader[] $expenseHeaders
 * @property IndentHeader[] $indentHeaders
 * @property Inventory[] $inventories
 * @property JournalAccounting[] $journalAccountings
 * @property JournalVoucherHeader[] $journalVoucherHeaders
 * @property PurchaseCheque[] $purchaseCheques
 * @property PurchaseHeader[] $purchaseHeaders
 * @property PurchaseInvoice[] $purchaseInvoices
 * @property PurchasePaymentHeader[] $purchasePaymentHeaders
 * @property PurchaseReceiptHeader[] $purchaseReceiptHeaders
 * @property PurchaseReturnHeader[] $purchaseReturnHeaders
 * @property SaleCheque[] $saleCheques
 * @property SaleDownpayment[] $saleDownpayments
 * @property SaleInvoice[] $saleInvoices
 * @property SalePaymentHeader[] $salePaymentHeaders
 * @property SaleReceiptHeader[] $saleReceiptHeaders
 * @property SaleReturnHeader[] $saleReturnHeaders
 * @property TaxForm[] $taxForms
 * @property TransferHeader[] $transferHeaders
 */
class AdminBase extends ActiveRecord {

	public $current_password = '';
	public $new_password = '';
	public $confirm_password = '';
	public $roles = array();
	
    public function tableName() {
        return 'tblla_admin';
    }

    public function rules() {
        return array(

            array('username, name' , 'required'),

            array('email', 'email'),
            array('username', 'unique'),
            array('branch_id, is_inactive', 'numerical', 'integerOnly' => true),
            array('username, name, phone, email', 'length', 'max' => 60),
            array('password', 'length', 'max' => 32),
			array('current_password, new_password, confirm_password', 'length', 'max' => 32),
			array('new_password, confirm_password', 'required', 'on' => 'insert'),
			array('confirm_password', 'compare', 'compareAttribute' => 'new_password'),
			array('current_password', 'authenticate', 'on' => 'update'),
            array('address, roles, note', 'safe'),
            // The following rule is used by search().
            array('id, username, password, name, address, phone, email, note, branch_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'adjustmentHeaders' => array(self::HAS_MANY, 'AdjustmentHeader', 'admin_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'deliveryHeaders' => array(self::HAS_MANY, 'DeliveryHeader', 'admin_id'),
            'depositHeaders' => array(self::HAS_MANY, 'DepositHeader', 'admin_id'),
            'expenseHeaders' => array(self::HAS_MANY, 'ExpenseHeader', 'admin_id'),
            'indentHeaders' => array(self::HAS_MANY, 'IndentHeader', 'admin_id'),
            'inventories' => array(self::HAS_MANY, 'Inventory', 'admin_id'),
            'journalAccountings' => array(self::HAS_MANY, 'JournalAccounting', 'admin_id'),
            'journalVoucherHeaders' => array(self::HAS_MANY, 'JournalVoucherHeader', 'admin_id'),
            'purchaseCheques' => array(self::HAS_MANY, 'PurchaseCheque', 'admin_id'),
            'purchaseHeaders' => array(self::HAS_MANY, 'PurchaseHeader', 'admin_id'),
            'purchaseInvoices' => array(self::HAS_MANY, 'PurchaseInvoice', 'admin_id'),
            'purchasePaymentHeaders' => array(self::HAS_MANY, 'PurchasePaymentHeader', 'admin_id'),
            'purchaseReceiptHeaders' => array(self::HAS_MANY, 'PurchaseReceiptHeader', 'admin_id'),
            'purchaseReturnHeaders' => array(self::HAS_MANY, 'PurchaseReturnHeader', 'admin_id'),
            'saleCheques' => array(self::HAS_MANY, 'SaleCheque', 'admin_id'),
            'saleDownpayments' => array(self::HAS_MANY, 'SaleDownpayment', 'admin_id'),
            'saleInvoices' => array(self::HAS_MANY, 'SaleInvoice', 'admin_id'),
            'salePaymentHeaders' => array(self::HAS_MANY, 'SalePaymentHeader', 'admin_id'),
            'saleReceiptHeaders' => array(self::HAS_MANY, 'SaleReceiptHeader', 'admin_id'),
            'saleReturnHeaders' => array(self::HAS_MANY, 'SaleReturnHeader', 'admin_id'),
            'taxForms' => array(self::HAS_MANY, 'TaxForm', 'admin_id'),
            'transferHeaders' => array(self::HAS_MANY, 'TransferHeader', 'admin_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'note' => 'Note',
            'branch_id' => 'Branch',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('branch_id', $this->branch_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}