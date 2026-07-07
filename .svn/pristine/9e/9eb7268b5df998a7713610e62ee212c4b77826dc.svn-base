<?php

class Indent extends CComponent
{
	public $header;
	public $details;

	public function __construct($header, array $details)
	{
		$this->header = $header;
		$this->details = $details;
	}
    
    public function generateCodeNumber($branchId, $currentMonth, $currentYear)
	{
		$indentHeader = IndentHeader::model()->find(array(
			'order' => 'id DESC',
			'condition' => 'branch_id = :branch_id',
			'params' => array(':branch_id' => $branchId),
		));
		
		if ($indentHeader !== null)
			$this->header->setCodeNumber($indentHeader->cn_ordinal, $indentHeader->cn_month, $indentHeader->cn_year, $indentHeader->branch_id);
		
		$this->header->setCodeNumberByNext($currentMonth, $currentYear);
	}

	public function addDetail($id)
	{
		$product = Product::model()->findByPk($id);

		if ($product !== null)
		{
			$exist = false;
			foreach ($this->details as $i => $detail)
			{
				if ($product->id === $detail->product_id)
				{
					$exist = true;
					break;
				}
			}

			if ($exist)
				$this->details[$i]->quantity++;
			else
			{
				$detail = new IndentDetail();
				$detail->product_id = $product->id;
				$this->details[] = $detail;
			}
		}
	}

	public function removeDetailAt($index)
	{
		array_splice($this->details, $index, 1);
	}

	public function validate()
	{
		$valid = $this->header->validate();
		
		$valid = $this->validateDetailsCount() && $valid;

		if (count($this->details) > 0)
		{
			foreach ($this->details as $detail)
			{
				$fields = array('quantity', 'unit_price');
				$valid = $detail->validate($fields) && $valid;
			}
		}
		else
			$valid = false;

		return $valid;
	}
	
	public function validateDetailsCount()
	{
		$valid = true;
		if (count($this->details) === 0)
		{
			$valid = false;
			$this->header->addError('error', 'Form tidak ada data untuk insert database. Minimal satu data detail untuk melakukan penyimpanan.');
		}
		
		return $valid;
	}
	
	public function flush()
	{
		$valid = $this->header->save(false);
		foreach ($this->details as $detail)
		{
			if ($detail->quantity <= 0) continue;
			
			if ($detail->isNewRecord)
				$detail->indent_header_id = $this->header->id;
			
			$valid = $detail->save(false) && $valid;
		}

		return $valid;
	}

	public function save($dbConnection)
	{
        $dbTransaction = $dbConnection->beginTransaction();
		try
		{
			$valid = $this->validate() && $this->flush();
			if ($valid)
				$dbTransaction->commit();
			else
				$dbTransaction->rollback();
		}
		catch (Exception $e)
		{
			$dbTransaction->rollback();
			$valid = false;
		}
		return $valid;
    
	}

	public function getGrandTotal()
	{
		$total = 0.00;
		foreach ($this->details as $detail)
			$total += $detail->total;

		return $total;
	}
}
