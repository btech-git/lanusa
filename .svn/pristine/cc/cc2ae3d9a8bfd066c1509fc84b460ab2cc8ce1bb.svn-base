<?php

class AccountCategoryType extends AccountCategoryTypeBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getBalanceTotal($endDate, $branchId)
	{
		$balanceTotal = 0.00;

		foreach ($this->accountCategories as $accountCategory)
			$balanceTotal += $accountCategory->getBalanceTotal($endDate, $branchId);

		return $balanceTotal;
	}
}