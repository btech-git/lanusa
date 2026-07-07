<?php

class SaleHeader extends SaleHeaderBase {

    const CN_CONSTANT = 'SLE';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function searchByDelivery() {
        $dataProvider = $this->search();

        $dataProvider->criteria->addCondition('
            EXISTS (
                SELECT p.quantity - COALESCE(SUM(r.quantity), 0) AS quantity_sale
                FROM ' . SaleDetail::model()->tableName() . ' p
                LEFT OUTER JOIN ' . DeliveryDetail::model()->tableName() . ' r
                ON p.id = r.sale_detail_id AND r.is_inactive = 0 
                WHERE t.id = p.sale_header_id AND p.is_inactive = 0
                GROUP BY p.id
                HAVING quantity_sale > 0
            )
        ');

        return $dataProvider;
    }

    public function searchByItemDelivered($isNonTax = null) {
        //search sale header which sold quantity is not fully delivered yet
        $criteria = new CDbCriteria;

        $criteria->condition = "EXISTS (
            SELECT s.quantity - SUM(COALESCE(d.quantity, 0)) AS quantity_sold
            FROM " . SaleDetail::model()->tableName() . " s
            LEFT OUTER JOIN " . Product::model()->tableName() . " product ON product.id = s.product_id
            LEFT OUTER JOIN " . DeliveryDetail::model()->tableName() . " d ON d.product_id = product.id
            WHERE t.id = s.sale_header_id AND s.is_inactive = 0
            GROUP BY s.id
            HAVING quantity_sold > 0
        )";

        if ($isNonTax !== null) {
            $criteria->addCondition('t.is_non_tax = :is_non_tax');
            $criteria->params[':is_non_tax'] = intval($isNonTax);
        }

//		$criteria->compare('number', $this->number, true);
        $criteria->compare('cn_ordinal', $this->cn_ordinal, true);
        $criteria->compare('cn_month', $this->cn_month, true);
        $criteria->compare('cn_year', $this->cn_year, true);

        $criteria->compare('date', $this->date, true);
        $criteria->compare('customer_id', $this->customer_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchByInvoice($nt) {
        $criteria = new CDbCriteria;

        $criteria->condition = "t.id NOT IN (SELECT sale_header_id FROM tblla_sale_invoice WHERE is_inactive = 0) AND t.is_non_tax = {$nt}";

        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.customer_id', $this->customer_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function getSubTotal() {
        $total = 0.00;

        foreach ($this->saleDetails as $detail) {
            $total += $detail->total;
        }

        return $total;
    }

    public function getCostOfGoodsSold() {

        return $this->subTotal * 11 / 12;
    }

//	public function getCalculatedDiscount()
//	{
//		return $this->subTotal * $this->discount / 100;
//	}

    public function getTotalBeforeTax() {
        return $this->subTotal - $this->discount - (($this->saleDownpayment == null) ? 0 : $this->saleDownpayment->amount) + $this->shipping_fee;
    }

    public function getCalculatedTax() {
        return $this->totalBeforeTax * $this->tax / 100;
    }

    public function getGrandTotal() {
        return $this->totalBeforeTax + $this->calculatedTax;
    }

    public function getTotalPayment() {
        $total = $this->grandTotal;

        foreach ($this->deliveryHeaders as $deliveryHeader)
            foreach ($deliveryHeader->saleInvoices as $invoiceHeader)
                foreach ($invoiceHeader->saleReturnHeaders as $saleReturnHeader)
                    $total -= $saleReturnHeader->grandTotal;

        return $total;
    }

    public function getTotalInvoiceReport() {
        $total = 0.00;

        foreach ($this->deliveryHeaders as $deliveryHeader) {
            foreach ($deliveryHeader->saleInvoices as $invoiceHeader)
                $total += $invoiceHeader->grandTotal;
        }

        return $total;
    }

    public function getOutstandingDelivery() {

        return $this->grandTotal - $this->totalInvoiceReport;
    }

    public function getTotalQuantity() {
        $totalQuantity = 0;

        foreach ($this->saleDetails as $detail)
            $totalQuantity += $detail->quantity;

        return $totalQuantity;
    }

    public function getQuantityDeliveryRemaining() {
        $quantityDelivery = 0;

        foreach ($this->deliveryHeaders as $deliveryHeader) {
            if ($deliveryHeader->is_inactive == 0)
                $quantityDelivery += $deliveryHeader->totalQuantity;
        }

        return $this->totalQuantity - $quantityDelivery;
    }

    public function searchWithPaging() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.tax', $this->tax);
        $criteria->compare('t.discount', $this->discount, true);
        $criteria->compare('t.shipping_fee', $this->shipping_fee, true);
        $criteria->compare('t.driver', $this->driver, true);
        $criteria->compare('t.plate_number', $this->plate_number, true);
        $criteria->compare('t.note', $this->note, true);
        $criteria->compare('t.reference', $this->reference, true);
        $criteria->compare('t.customer_id', $this->customer_id);
        $criteria->compare('t.sale_downpayment_id', $this->sale_downpayment_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public static function makeChartAxisY($chartData, $part) {
        $total = 0;
        foreach ($chartData as $data) {
            if ($total < $data[1])
                $total = $data[1];
        }

        $top = $total * (1 + 1 / ($part * 2));

        $n = 0;
        while (floor($top) >= 100) {
            $top /= 10;
            $n++;
        }

        $top = floor($top);

        $top += $part - $top % $part;

        for ($i = 0; $i < $n; $i++) {
            $top *= 10;
        }

        return array('min' => 0, 'max' => $top, 'tickSize' => floor($top / $part));
    }

    public static function makeChartAxisX($chartData) {
        $labels = array();
        foreach ($chartData as $data)
            $labels[] = array($data[0], substr($data[2], 6, 8));

        return array('min' => 0, 'max' => count($chartData) + 1, 'ticks' => $labels);
    }

    public static function makeChartData($backNum) {
        $data = array();
        $data['color'] = 'green';
        $data['data'] = array();

        $dataRows = array();
        $dateList = array();
        for ($i = $backNum; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-{$i} months"));
            $dataRows[$date] = array('total' => 0);
            $dateList[] = $date;
        }

        $sql = "SELECT SUBSTRING(h.date, 1, 7) AS date, SUM(d.quantity * d.unit_price*(1-d.discount/100)) AS total
				FROM " . SaleHeader::model()->tableName() . " h 
                INNER JOIN " . SaleDetail::model()->tableName() . " d ON h.id = d.sale_header_id
				WHERE SUBSTRING(h.date, 1, 7) <= :end AND SUBSTRING(h.date, 1, 7) >= :start
				GROUP BY SUBSTRING(h.date, 1, 7)";

        $rows = CActiveRecord::$db->createCommand($sql)->queryAll(true, array(
            ':start' => $dateList[0],
            ':end' => $dateList[$backNum],
        ));

        foreach ($rows as $row) {
            if (in_array($row['date'], $dateList))
                $dataRows[$row['date']]['total'] = $row['total'];
        }

        $counter = 1;
        foreach ($dataRows as $dateLiteral => $dataRow) {
            $tickLabel = date('M Y', strtotime($dateLiteral));
            $total = number_format($dataRow['total'], 2);
            $tooltip = "DATE: {$tickLabel}<br />TOTAL: {$total}";
            $data['data'][] = array($counter, $dataRow['total'], $tooltip);
            $counter++;
        }

        return $data;
    }

}
