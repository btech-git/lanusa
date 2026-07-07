<?php

class CodeNumber
{
	public static function make($models, $attribute, $constant, $isMonthly = false)
	{
		$record = (is_array($models)) ? self::makeFromMany($models, $attribute, $constant, $isMonthly) : self::makeFromOne($models, $attribute, $constant, $isMonthly);

		$months = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');

		$monthNow = date('m');
		$yearNow = date('y');

		if ($record === false)
			$ordinal = 0;
		else
		{
			if ($isMonthly)
				list($ordinal,, $month, $year) = explode('/', $record[$attribute]) + array(0, $constant, $months[$monthNow - 1], $yearNow);
			else
				list($ordinal,, $year) = explode('/', $record[$attribute]) + array(0, $constant, $yearNow);

			$valid = $year < $yearNow;

			if ($isMonthly)
				$valid = $valid || ((($month === 'IX') ? 'VIIII' : $month) < (($months[$monthNow - 1] === 'IX') ? 'VIIII' : $months[$monthNow - 1]));

			if ($valid)
				$ordinal = 0;
		}

		if ($isMonthly)
			$codeNumber = sprintf('%04d/%s/%s/%d', $ordinal + 1, $constant, $months[$monthNow - 1], $yearNow);
		else
			$codeNumber = sprintf('%04d/%s/%d', $ordinal + 1, $constant, $yearNow);

		return $codeNumber;
	}

	private static function makeFromOne($model, $attribute, $constant, $isMonthly)
	{
		if ($isMonthly)
		{
			$select = "{$attribute}, SUBSTRING_INDEX({$attribute}, '/', 1) AS ordinal, CASE SUBSTRING_INDEX(SUBSTRING_INDEX({$attribute}, '/', -2), '/', 1) WHEN 'IX' THEN 'VIIII' ELSE SUBSTRING_INDEX(SUBSTRING_INDEX({$attribute}, '/', -2), '/', 1) END AS month, SUBSTRING_INDEX({$attribute}, '/', -1) AS year";
			$order = 'year DESC, month DESC, ordinal DESC';
		}
		else
		{
			$select = "{$attribute}, SUBSTRING_INDEX({$attribute}, '/', 1) AS ordinal, SUBSTRING_INDEX({$attribute}, '/', -1) AS year";
			$order = 'year DESC, ordinal DESC';
		}
		
		$record = CActiveRecord::$db->createCommand()
			->select($select)
			->from($model->tableName())
			->where("SUBSTRING_INDEX(SUBSTRING_INDEX(number, '/', 2), '/', -1) = '{$constant}'")
			->order($order)
			->queryRow();

		return $record;
	}

	private static function makeFromMany($models, $attribute, $constant, $isMonthly)
	{
		if ($isMonthly)
		{
			$select = "{$attribute}, SUBSTRING_INDEX({$attribute}, '/', 1) AS ordinal, CASE SUBSTRING_INDEX(SUBSTRING_INDEX({$attribute}, '/', -2), '/', 1) WHEN 'IX' THEN 'VIIII' ELSE SUBSTRING_INDEX(SUBSTRING_INDEX({$attribute}, '/', -2), '/', 1) END AS month, SUBSTRING_INDEX({$attribute}, '/', -1) AS year";
			$order = 'year DESC, month DESC, ordinal DESC';
		}
		else
		{
			$select = "{$attribute}, SUBSTRING_INDEX({$attribute}, '/', 1) AS ordinal, SUBSTRING_INDEX({$attribute}, '/', -1) AS year";
			$order = 'year DESC, ordinal DESC';
		}
		
		$sql = '';
		foreach ($models as $i=>$model)
		{
			if ($i > 0)
				$sql .= " UNION ";

			$sql .= "SELECT {$select}
					FROM " . $model->tableName() . "
					WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(number, '/', 2), '/', -1) = '{$constant}'";
		}
		$sql .= (count($models) > 0) ? " ORDER BY {$order}" : '';

		$record = CActiveRecord::$db->createCommand($sql)->queryRow();

		return $record;
	}
	
	public static function setTaxNumber($taxForm, $modelType)
	{
		if ($modelType === 1)
			$model = $taxForm->salesDownpayment;
		else if ($modelType === 2)
			$model = $taxForm->invoiceHeader;
		else
			$model = null;
		
		if ($model === null)
		{
			$ordinal = 0;
			$year = 0;
		}
		else
			list($ordinal, , $year) = explode('/', $model->number) + array(0, 0, 0);
		
		$taxForm->cn_ordinal = 3537 + $ordinal;
		$taxForm->cn_constant = sprintf('010.000-%02d.4327', $year);
	}
}
