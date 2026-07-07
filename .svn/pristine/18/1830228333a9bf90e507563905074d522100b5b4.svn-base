<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_year
 * @property string $cn_constant
 * @property integer $sale_invoice_id
 * @property integer $sale_downpayment_id
 * @property integer $branch_id
 * @property integer $admin_id
 * @property integer $is_inactive
 *
 * @property Admin $admin
 * @property SaleInvoice $saleInvoice
 * @property SaleDownpayment $saleDownpayment
 * @property Branch $branch
 */
class TaxFormBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_tax_form';
	}

	public function rules()
	{
		return array(
			array('cn_ordinal, cn_year, cn_constant, branch_id, admin_id', 'required'),
			array('cn_ordinal, cn_year, sale_invoice_id, sale_downpayment_id, branch_id, admin_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('cn_constant', 'length', 'max'=>20),
			// The following rule is used by search().
			array('id, cn_ordinal, cn_year, cn_constant, sale_invoice_id, sale_downpayment_id, branch_id, admin_id, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
			'saleInvoice' => array(self::BELONGS_TO, 'SaleInvoice', 'sale_invoice_id'),
			'saleDownpayment' => array(self::BELONGS_TO, 'SaleDownpayment', 'sale_downpayment_id'),
			'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cn_ordinal' => 'Cn Ordinal',
			'cn_year' => 'Cn Year',
			'cn_constant' => 'Cn Constant',
			'sale_invoice_id' => 'Sale Invoice',
			'sale_downpayment_id' => 'Sale Downpayment',
			'branch_id' => 'Branch',
			'admin_id' => 'Admin',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('cn_ordinal', $this->cn_ordinal);
		$criteria->compare('cn_year', $this->cn_year);
		$criteria->compare('cn_constant', $this->cn_constant, true);
		$criteria->compare('sale_invoice_id', $this->sale_invoice_id);
		$criteria->compare('sale_downpayment_id', $this->sale_downpayment_id);
		$criteria->compare('branch_id', $this->branch_id);
		$criteria->compare('admin_id', $this->admin_id);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}