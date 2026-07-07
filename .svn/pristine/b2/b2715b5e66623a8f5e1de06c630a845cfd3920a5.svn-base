<?php

class TaxConnectionChecking extends CComponent
{
	public static function taxValid()
	{
		return self::taxSessionValid('t');
	}
	
	public static function taxSecondaryValid()
	{
		return self::taxSessionValid('ts');
	}

	public static function nonTaxValid()
	{
		return self::taxSessionValid('nt');
	}

	public static function isCurrentConnectionPrimary()
	{
		return (isset(Yii::app()->session['DatabaseConnection']) && Yii::app()->session['DatabaseConnection'] === '1') || !isset(Yii::app()->session['DatabaseConnection']);
	}

	public static function isCurrentConnectionSecondary()
	{
		return (isset(Yii::app()->session['DatabaseConnection']) && Yii::app()->session['DatabaseConnection'] === '2');
	}
	
	private static function taxSessionValid($prefix)
	{
		$tasks = array('Accounting', 'Delivery', 'Invoice', 'Purchase', 'PurchasePayment', 'Receive', 'SalesPayment', 'Warehouse');
		$operations = array('Create', 'Edit', 'Report');

		$valid = false;

		foreach ($tasks as $task)
		{
			$role = $prefix . $task;
			if (!$valid)
				$valid = Yii::app()->user->checkAccess($role) || $valid;

			foreach ($operations as $operation)
			{
				$role2 = $role . $operation;
				if (!$valid)
					$valid = Yii::app()->user->checkAccess($role2) || $valid;
			}
		}

		return $valid;
	}
}