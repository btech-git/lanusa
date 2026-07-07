<?php

class PurchaseDetail extends PurchaseDetailBase {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getTotal() {
        return $this->quantity * $this->unit_price;
    }

    public function getUnitPrice() {
        return ((int) $this->purchaseHeader->is_non_tax == PurchaseHeader::INCLUDE_TAX) ? $this->unit_price / (1 + ($this->purchaseHeader->tax / 100)) : $this->unit_price;
    }

    public function getTotalReport() {
        return ((int) $this->purchaseHeader->is_non_tax == PurchaseHeader::INCLUDE_TAX) ? $this->getTotal() / (1 + ($this->purchaseHeader->tax / 100)) : $this->getTotal();
    }
}
