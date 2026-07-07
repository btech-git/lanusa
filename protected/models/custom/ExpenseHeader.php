<?php

class ExpenseHeader extends ExpenseHeaderBase
{
	const CN_CONSTANT_CASH = 'EXPC';
	const CN_CONSTANT_BANK = 'EXPB';
	
	const DISAPPROVED = 0;
	const APPROVED = 1;
	const DISAPPROVED_LITERAL = 'NOT Approved';
	const APPROVED_LITERAL = 'APPROVED';

	public function getApprovalStatus()
	{
		return ($this->is_approved) ? self::APPROVED_LITERAL : self::DISAPPROVED_LITERAL;
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
    public function getTotal()
	{
		$total = 0.00;

		foreach ($this->expenseDetails as $detail)
		{
			if ($detail->is_inactive == 0)
				$total += $detail->amount;
		}

		return $total;
	}
}