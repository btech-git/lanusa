<?php

class Product extends ProductBase {

    public $stock = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getLocalStock($warehouseId) {
        $sql = SqlGenerator::localStock();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':warehouse_id' => $warehouseId,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getLocalStockItemPrice($warehouseId) {
        $sql = SqlGenerator::localStockItemPrice();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':warehouse_id' => $warehouseId,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getLocalStockPrice($warehouseId) {
        $sql = SqlGenerator::localStockPrice();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':warehouse_id' => $warehouseId,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getGlobalStock() {
        $sql = SqlGenerator::globalStock();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getGlobalStockItemPrice() {
        $sql = SqlGenerator::globalStockItemPrice();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getGlobalStockPrice() {
        $sql = SqlGenerator::globalStockPrice();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function searchByLocalStock() {
        $criteria = new CDbCriteria;

//		if (empty($branch))
//		{
        $criteria->condition = "EXISTS (
				SELECT COALESCE(SUM(i.quantity_in - i.quantity_out), 0) AS stock
				FROM " . Inventory::model()->tableName() . " i
				WHERE t.id = i.product_id
				HAVING stock > 0
			)";
//		}
//		else
//		{
//			$criteria->condition = "EXISTS (
//				SELECT COALESCE(SUM(i.quantity_in - i.quantity_out), 0) AS stock
//				FROM " . Inventory::model()->tableName() ." i
//				WHERE t.id = i.product_id AND i.branch_id = :branch_id
//				HAVING stock > 0
//			)";
//			
//			$criteria->params[':branch_id'] = $branch;
//		}

        $criteria->compare('id', $this->id);
        $criteria->compare('t.code', $this->code, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('unit_id', $this->unit_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

//	public function getCurrentStock($branch)
//	{
//		if (empty($branch))
//		{
//			$sql = SqlGenerator::globalStock();
//
//			$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
//				':product_id' => $this->id,
//			));
//		}
//		else
//		{
//			$sql = SqlGenerator::globalStock() . " AND branch_id = :branch_id";
//
//			$value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
//				':product_id' => $this->id,
//				':branch_id' => $branch,
//			));
//		}
//
//		return ($value === false) ? 0 : $value;
//	}

    public function searchByGlobalStock() {
        $criteria = new CDbCriteria;

//		if (empty($branch))
//		{
        $criteria->condition = "EXISTS (
				SELECT COALESCE(SUM(i.quantity_in - i.quantity_out), 0) AS stock
				FROM " . Inventory::model()->tableName() . " i
				WHERE t.id = i.product_id
				HAVING stock > 0
			)";
//		}
//		else
//		{
//			$criteria->condition = "EXISTS (
//				SELECT COALESCE(SUM(i.quantity_in - i.quantity_out), 0) AS stock
//				FROM " . Inventory::model()->tableName() ." i
//				WHERE t.id = i.product_id AND i.branch_id = :branch_id
//				HAVING stock > 0
//			)";
//			
//			$criteria->params[':branch_id'] = $branch;
//		}

        $criteria->compare('id', $this->id);
        $criteria->compare('t.code', $this->code, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('unit_id', $this->unit_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function getStockBeginning($startDate) {
        $sql = SqlGenerator::stockBeginning();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':start_date' => $startDate,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getStockEnding($endDate) {
        $sql = SqlGenerator::stockEnding();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':end_date' => $endDate,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getInventoryReportStockFromBeginningTo($date, $warehouse, $isInclusive = false) {
        $sql = "SELECT COALESCE(SUM(quantity_in - quantity_out), 0) 
				FROM " . Inventory::model()->tableName() . " 
                WHERE product_id = :product_id";

        if ($isInclusive) {
            $sql .= " AND date <= :date";
        } else {
            $sql .= " AND date < :date";
        }

        if (empty($warehouse)) {
            $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
                ':product_id' => $this->id,
                ':date' => $date,
            ));
        } else {
            $sql .= " AND warehouse_id = :warehouse_id";

            $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
                ':product_id' => $this->id,
                ':date' => $date,
                ':warehouse_id' => $warehouse,
            ));
        }

        return ($value === false) ? 0 : $value;
    }

    public function getInventoryReportStockBranchFromBeginningTo($date, $isInclusive = false, $branchId) {
        $sql = "SELECT COALESCE(SUM(quantity_in - quantity_out), 0) 
				FROM " . Inventory::model()->tableName() . " WHERE product_id = :product_id AND branch_id = :branch_id";

        if ($isInclusive)
            $sql .= " AND date <= :date";
        else
            $sql .= " AND date < :date";

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':date' => $date,
            ':branch_id' => $branchId,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getStockIn($startDate, $endDate) {
        $sql = SqlGenerator::stockIn();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getStockOut($startDate, $endDate) {
        $sql = SqlGenerator::stockOut();

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(
            ':product_id' => $this->id,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        ));

        return ($value === false) ? 0 : $value;
    }

    public function getTotalQuantitySales() {
        $totalQuantitySales = 0.00;

        foreach ($this->saleDetails as $saleDetail)
            $totalQuantitySales += $saleDetail->quantity;

        return $totalQuantitySales;
    }

    public function getTotalSales() {
        $totalSales = 0.00;

        foreach ($this->saleDetails as $saleDetail)
            $totalSales += $saleDetail->total;

        return $totalSales;
    }

    public function getTotalQuantityPurchase() {
        $totalQuantityPurchase = 0.00;

        foreach ($this->purchaseDetails as $purchaseDetail)
            $totalQuantityPurchase += $purchaseDetail->quantity;

        return $totalQuantityPurchase;
    }

    public function getTotalQuantityReceive() {
        $totalQuantityReceive = 0.00;

        foreach ($this->receiveDetails as $receiveDetail)
            $totalQuantityReceive += $receiveDetail->quantity;

        return $totalQuantityReceive;
    }

    public function getTotalPurchase() {
        $totalPurchase = 0.00;

        foreach ($this->purchaseDetails as $purchaseDetail)
            $totalPurchase += $purchaseDetail->total;

        return $totalPurchase;
    }

    public function getPurchasePerItem() {
        $purchasePerItem = 0.00;

        foreach ($this->purchaseDetails as $purchaseDetail)
            $purchasePerItem = $purchaseDetail->total * (1 - $purchaseDetail->purchaseHeader->discount / 100) * (1 + $purchaseDetail->purchaseHeader->tax / 100);

        return $purchasePerItem;
    }

    public function getTotalReceive() {
        $totalReceive = 0.00;

        foreach ($this->receiveDetails as $receiveDetail)
            $totalReceive += $receiveDetail->total;

        return $totalReceive;
    }

    public function getCostOfGoodsSold() {
        $cogs = 0.00;

        if ($this->totalQuantityPurchase > 0)
            $cogs = $this->totalPurchase / $this->totalQuantityPurchase;

        return $cogs;
    }

//	public function searchByCogs()
//	{
//		$cogs = 0.00;
//
//		if ($this->totalQuantityPurchase > 0)
//			$cogs = $this->totalPurchase / $this->totalQuantityPurchase;
//
//		return $cogs;
//	}

    public function searchInInventory() {
        $criteria = new CDbCriteria;

        $criteria->condition = 't.id IN (
			SELECT product_id 
			FROM tblla_purchase_detail 
			GROUP BY product_id
			HAVING SUM(quantity * unit_price) / SUM(quantity) > 0
		)';

        $criteria->condition = 't.id IN (SELECT product_id FROM tblla_inventory)';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchByCogs() {
        $criteria = new CDbCriteria;

        //$criteria->condition = 't.id IN (SELECT (SUM(quantity * unit_price) / SUM(quantity)) AS cogs FROM tblla_purchase_detail HAVING SUM(quantity * unit_price) / SUM(quantity) > 0)';
//		$criteria->condition = 't.id IN (
//				SELECT product_id
//				FROM tblla_purchase_detail
//				GROUP BY product_id
//				HAVING SUM(quantity * unit_price) / SUM(quantity) > 0
//			)';

        $criteria->condition = 'EXISTS (
			SELECT d.product_id
			FROM tblla_purchase_detail d
			WHERE t.id = d.product_id AND d.is_inactive = 0
			GROUP BY d.product_id
			HAVING SUM(d.quantity * d.unit_price) / SUM(d.quantity) > 0
		)';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchBySaleItem($startDate, $endDate, $category, $branch) {
        $startDate = (empty($startDate)) ? date('Y-m-d') : $startDate;
        $endDate = (empty($endDate)) ? date('Y-m-d') : $endDate;

        $criteria = new CDbCriteria;
        $criteria->condition = 'category_id = :category AND 
				t.id IN (
					SELECT product_id
					FROM tblla_sale_detail d JOIN tblla_sale_header h
					ON d.sale_header_id = h.id
					WHERE 
					h.date BETWEEN :startDate AND :endDate
					AND 
					(d.quantity * d.unit_price) > 0
					AND 
					h.branch_id = :branch
					
				)

			';
        $criteria->params = array(':category' => $category, 'startDate' => $startDate, 'endDate' => $endDate, 'branch' => $branch);

//		$criteria->condition = 't.id IN (SELECT product_id FROM tblla_inventory)';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchForPurchaseItem($startDate, $endDate, $branchId) {
        $criteria = new CDbCriteria;
//	
//		$criteria->condition = 't.id IN (
//			SELECT product_id 
//			FROM tblla_purchase_detail 
//			GROUP BY product_id
//			HAVING SUM(quantity * unit_price) / SUM(quantity) > 0
//		)';
//		
//		$criteria->condition = 't.id IN (SELECT product_id FROM tblla_inventory)';
//		$criteria->condition='EXISTS (
//			SELECT d.product_id
//			FROM tblla_purchase_detail d
//			INNER JOIN tblla_purchase_header h ON h.id = d.purchase_header_id
//			WHERE d.product_id = t.id AND h.branch_id = :branch_id AND h.date BETWEEN :start_date AND :end_date
//			AND d.is_inactive = 0 AND h.is_inactive = 0
//		)';
//		
//		$criteria->params[':branch_id'] = $branchId;
//		$criteria->params[':start_date'] = $startDate;
//		$criteria->params[':end_date'] = $endDate;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
