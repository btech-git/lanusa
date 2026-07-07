<?php

class Bank extends BankBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getInfo()
	{
		return $this->number . ' - ' . $this->name;
	}
}