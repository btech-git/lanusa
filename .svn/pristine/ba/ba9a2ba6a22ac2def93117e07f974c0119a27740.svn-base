<?php

class PurchaseReceiptDetail extends PurchaseReceiptDetailBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getPurchaseAmount()
	{
		return ($this->receiveHeader === null) ? 0.00 : $this->receiveHeader->totalPurchase;
	}
	
	public function getCalculatedTax()
	{
		return ($this->receiveHeader === null) ? 0.00 : $this->purchaseAmount * $this->receiveHeader->purchaseHeader->tax / 100;
	}
    
    public function getTotalPurchase() {
        
        return $this->purchaseAmount + $this->calculatedTax;
    }
}