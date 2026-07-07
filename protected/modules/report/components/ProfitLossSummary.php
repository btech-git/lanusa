<?php
class ProfitLossSummary extends CComponent 
{
	public static function getAccountList($branchId) 
	{
		$accounts = array();
		
		//find sale
		$criteria = new CDbCriteria();
		$criteria->join = '
			JOIN '. Account::model()->tableName(). ' accounts ON accounts.account_category_id = t.id
		';
		$criteria->compare('accounts.branch_id', $branchId);
		$criteria->compare('t.name', 'PENJUALAN');
		$accounts['sale'] = AccountCategory::model()->find($criteria);
		
		//find purchase
		$criteria = new CDbCriteria();
		$criteria->join = '
			JOIN '. Account::model()->tableName(). ' accounts ON accounts.account_category_id = t.id
		';
		$criteria->compare('accounts.branch_id', $branchId);
		$criteria->compare('t.name', 'PEMBELIAN');
		$accounts['purchase'] = AccountCategory::model()->find($criteria);
		
		//find other income
		$criteria = new CDbCriteria();
		$criteria->join = '
			JOIN '. Account::model()->tableName(). ' accounts ON accounts.account_category_id = t.id
		';
		$criteria->compare('accounts.branch_id', $branchId);
		$criteria->compare('t.name', 'PENDAPATAN LAIN - LAIN');
		$accounts['otherIncome'] = AccountCategory::model()->find($criteria);
		
		//find other expense
		$criteria = new CDbCriteria();
		$criteria->join = '
			JOIN '. Account::model()->tableName(). ' accounts ON accounts.account_category_id = t.id
		';
		$criteria->compare('accounts.branch_id', $branchId);
		$criteria->compare('t.name', 'BEBAN LAIN - LAIN');
		$accounts['otherExpense'] = AccountCategory::model()->find($criteria);
		
		//find expense
		$criteria = new CDbCriteria();
		$criteria->join = '
			JOIN '. AccountCategory::model()->tableName(). ' accountCategories ON accountCategories.account_category_type_id = t.id
			JOIN '. Account::model()->tableName(). ' accounts ON accounts.account_category_id = accountCategories.id
		';
		$criteria->compare('t.name', 'BEBAN');
		$accounts['expense'] = AccountCategoryType::model()->find($criteria);
		
		return $accounts;
	}
}
