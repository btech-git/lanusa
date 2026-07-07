<?php

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $province
 * @property integer $zip_code
 * @property string $phone
 * @property string $fax
 * @property string $npwp
 * @property string $bank_account
 * @property integer $is_tax
 * @property integer $is_inactive
 *
 * @property Account[] $accounts
 * @property AdjustmentHeader[] $adjustmentHeaders
 * @property Admin[] $admins
 * @property Customer[] $customers
 * @property DeliveryHeader[] $deliveryHeaders
 * @property DepositHeader[] $depositHeaders
 * @property ExpenseHeader[] $expenseHeaders
 * @property Inventory[] $inventories
 * @property JournalAccounting[] $journalAccountings
 * @property JournalVoucherHeader[] $journalVoucherHeaders
 * @property PurchaseCheque[] $purchaseCheques
 * @property PurchaseHeader[] $purchaseHeaders
 * @property PurchaseInvoiceHeader[] $purchaseInvoiceHeaders
 * @property PurchasePaymentHeader[] $purchasePaymentHeaders
 * @property PurchaseReceiptHeader[] $purchaseReceiptHeaders
 * @property PurchaseReturnHeader[] $purchaseReturnHeaders
 * @property ReceiveHeader[] $receiveHeaders
 * @property SaleChequeHeader[] $saleChequeHeaders
 * @property SaleDownpayment[] $saleDownpayments
 * @property SaleHeader[] $saleHeaders
 * @property SaleInvoice[] $saleInvoices
 * @property SalePaymentHeader[] $salePaymentHeaders
 * @property SaleReceiptHeader[] $saleReceiptHeaders
 * @property SaleReturnHeader[] $saleReturnHeaders
 * @property Supplier[] $suppliers
 * @property TaxForm[] $taxForms
 * @property TransferHeader[] $transferHeaders
 */
class BranchBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_branch';
	}

	public function rules()
	{
		return array(
			array('code, name, address, npwp', 'required'),
			array('zip_code, is_tax, is_inactive', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>20),
			array('name, city, province, phone, fax, npwp', 'length', 'max'=>60),
			array('bank_account', 'length', 'max'=>200),
			// The following rule is used by search().
			array('id, code, name, address, city, province, zip_code, phone, fax, npwp, bank_account, is_tax, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'accounts' => array(self::HAS_MANY, 'Account', 'branch_id'),
			'adjustmentHeaders' => array(self::HAS_MANY, 'AdjustmentHeader', 'branch_id'),
			'admins' => array(self::HAS_MANY, 'Admin', 'branch_id'),
			'customers' => array(self::HAS_MANY, 'Customer', 'branch_id'),
			'deliveryHeaders' => array(self::HAS_MANY, 'DeliveryHeader', 'branch_id'),
			'depositHeaders' => array(self::HAS_MANY, 'DepositHeader', 'branch_id'),
			'expenseHeaders' => array(self::HAS_MANY, 'ExpenseHeader', 'branch_id'),
			'inventories' => array(self::HAS_MANY, 'Inventory', 'branch_id'),
			'journalAccountings' => array(self::HAS_MANY, 'JournalAccounting', 'branch_id'),
			'journalVoucherHeaders' => array(self::HAS_MANY, 'JournalVoucherHeader', 'branch_id'),
			'purchaseCheques' => array(self::HAS_MANY, 'PurchaseCheque', 'branch_id'),
			'purchaseHeaders' => array(self::HAS_MANY, 'PurchaseHeader', 'branch_id'),
			'purchaseInvoiceHeaders' => array(self::HAS_MANY, 'PurchaseInvoiceHeader', 'branch_id'),
			'purchasePaymentHeaders' => array(self::HAS_MANY, 'PurchasePaymentHeader', 'branch_id'),
			'purchaseReceiptHeaders' => array(self::HAS_MANY, 'PurchaseReceiptHeader', 'branch_id'),
			'purchaseReturnHeaders' => array(self::HAS_MANY, 'PurchaseReturnHeader', 'branch_id'),
			'receiveHeaders' => array(self::HAS_MANY, 'ReceiveHeader', 'branch_id'),
			'saleChequeHeaders' => array(self::HAS_MANY, 'SaleChequeHeader', 'branch_id'),
			'saleDownpayments' => array(self::HAS_MANY, 'SaleDownpayment', 'branch_id'),
			'saleHeaders' => array(self::HAS_MANY, 'SaleHeader', 'branch_id'),
			'saleInvoices' => array(self::HAS_MANY, 'SaleInvoice', 'branch_id'),
			'salePaymentHeaders' => array(self::HAS_MANY, 'SalePaymentHeader', 'branch_id'),
			'saleReceiptHeaders' => array(self::HAS_MANY, 'SaleReceiptHeader', 'branch_id'),
			'saleReturnHeaders' => array(self::HAS_MANY, 'SaleReturnHeader', 'branch_id'),
			'suppliers' => array(self::HAS_MANY, 'Supplier', 'branch_id'),
			'taxForms' => array(self::HAS_MANY, 'TaxForm', 'branch_id'),
			'transferHeaders' => array(self::HAS_MANY, 'TransferHeader', 'branch_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'address' => 'Address',
			'city' => 'City',
			'province' => 'Province',
			'zip_code' => 'Zip Code',
			'phone' => 'Phone',
			'fax' => 'Fax',
			'npwp' => 'Npwp',
			'bank_account' => 'Bank Account',
			'is_tax' => 'Is Tax',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.code', $this->code, true);
		$criteria->compare('t.name', $this->name, true);
		$criteria->compare('t.address', $this->address, true);
		$criteria->compare('t.city', $this->city, true);
		$criteria->compare('t.province', $this->province, true);
		$criteria->compare('t.zip_code', $this->zip_code);
		$criteria->compare('t.phone', $this->phone, true);
		$criteria->compare('t.fax', $this->fax, true);
		$criteria->compare('t.npwp', $this->npwp, true);
		$criteria->compare('t.bank_account', $this->bank_account, true);
		$criteria->compare('t.is_tax', $this->is_tax);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}