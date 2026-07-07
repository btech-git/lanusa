<?php

class AccountingJournalHelper extends CComponent
{
	public static function make($type, $transactionNumber, $date, $accountId, $branchId, $total, $transactionType, $memo)
	{
//		$this->deleteRow($transactionNumber, $branchId, $transactionType);
		
		$accountingJournal = new JournalAccounting();
		$accountingJournal->transaction_number = $transactionNumber;
		$accountingJournal->account_id = $accountId;
		$accountingJournal->date = $date;
		$accountingJournal->admin_id = Yii::app()->user->id;
		$accountingJournal->branch_id = $branchId;
		$accountingJournal->type = $transactionType;
		$accountingJournal->memo = $memo;
		$accountingJournal->$type = $total;

		return $accountingJournal;
	}
	
	public static function deleteRow($transactionNumber, $branchId, $transactionType) 
	{
		JournalAccounting::model()->deleteAllByAttributes(array(
			'transaction_number' => $transactionNumber, 
//			'account_id' => $accountId, 
			'branch_id' => $branchId, 
			'type' => $transactionType
		));
	}
}
