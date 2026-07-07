<?php

class JournalVoucherHeader extends JournalVoucherHeaderBase
{
	const CN_CONSTANT = 'AJP';
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
    public function getTotalDebit()
	{
		$total = 0.00;

		foreach ($this->journalVoucherDetails as $detail)
			$total += $detail->debit;

		return $total;
	}

	public function getTotalCredit()
	{
		$total = 0.00;

		foreach ($this->journalVoucherDetails as $detail)
			$total += $detail->credit;

		return $total;
	}
}