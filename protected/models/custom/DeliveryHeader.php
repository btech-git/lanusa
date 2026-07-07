<?php

class DeliveryHeader extends DeliveryHeaderBase {
    const CN_CONSTANT = 'DLV';

    public $referenceNumber;
    public $customerName;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function search() {
        $dataProvider = parent::search();

        $dataProvider->criteria->with = array(
            'saleHeader:resetScope' => array(
                'with' => array(
                    'customer:resetScope'
                )
            )
        );

        $dataProvider->criteria->compare('saleHeader.reference', $this->referenceNumber, true);
        $dataProvider->criteria->compare('customer.name', $this->customerName, TRUE);

        $sort = new CSort;
        $sort->attributes = array(
            'customerName' => 'customer.name',
            'referenceNumber' => 'saleHeader.reference'
        );

        $dataProvider->sort = $sort;

        return $dataProvider;
    }

    public function searchByInvoice($nt) {
        $criteria = new CDbCriteria;

        $criteria->condition = "
            t.id NOT IN (
                SELECT delivery_header_id 
                FROM " . SaleInvoice::model()->tableName() . " 
                WHERE is_inactive = 0
            ) AND t.is_non_tax = {$nt}";

        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.branch_id', $this->branch_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function searchBySalesReturn() {
        $criteria = new CDbCriteria;

        $criteria->condition = "EXISTS (
			SELECT delivery.quantity - SUM(COALESCE(returned.quantity, 0)) AS quantity_sold
			FROM
			(
				SELECT h.id, d.quantity, d.product_id
				FROM " . DeliveryHeader::model()->tableName() . " h 
				INNER JOIN " . DeliveryDetail::model()->tableName() . " d ON h.id = d.delivery_header_id
				WHERE h.is_inactive = 0 AND d.is_inactive = 0
			) delivery
			LEFT OUTER JOIN
			(
				SELECT rh.delivery_header_id, rd.quantity, rd.product_id
				FROM " . SaleReturnHeader::model()->tableName() . " rh
				INNER JOIN " . SaleReturnDetail::model()->tableName() . " rd ON rh.id = rd.sales_return_header_id
				WHERE rh.is_inactive = 0 AND rd.is_inactive = 0
			) returned
			ON delivery.id = returned.delivery_header_id
			AND delivery.product_id = returned.product_id
			WHERE t.id = delivery.id
			GROUP BY delivery.id, delivery.product_id
			HAVING quantity_sold > 0
		)";

        $criteria->compare('cn_ordinal', $this->cn_ordinal, true);
        $criteria->compare('cn_month', $this->cn_month, true);
        $criteria->compare('cn_year', $this->cn_year, true);
        $criteria->compare('date', $this->date, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function searchByOutstandingInvoiceReport() {
        $criteria = new CDbCriteria;

        $criteria->condition = "
            t.id NOT IN (
                SELECT delivery_header_id 
                FROM " . SaleInvoice::model()->tableName() . " 
                WHERE is_inactive = 0
            )";

        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.branch_id', $this->branch_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function getTotalQuantity() {
        $total = 0;

        foreach ($this->deliveryDetails as $detail)
            $total += $detail->quantity;

        return $total;
    }

    public function getSubTotal() {
        $total = 0.00;

        foreach ($this->deliveryDetails as $detail)
            $total += $detail->total;

        return $total;
    }

    public function getTotalBeforeTax() {
        if (!empty($this->saleHeader)) {
            $saleDownpayment = ($this->saleHeader->saleDownpayment === null) ? 0 : $this->saleHeader->saleDownpayment->amount;

            return $this->subTotal - $this->saleHeader->discount - $saleDownpayment;
        }
        else
            return $this->subTotal;
    }

    public function getCalculatedTax() {
        $tax = ($this->saleHeader === null) ? 0.00 : $this->saleHeader->tax;

        if (!empty($this->saleHeader))
            return $this->totalBeforeTax * $tax / 100;
    }

    public function getGrandTotal() {
        return $this->totalBeforeTax + $this->calculatedTax + (($this->saleHeader === null) ? 0.00 : $this->saleHeader->shipping_fee);
    }

    public function getTotalPayment() {
        $total = $this->grandTotal;

        foreach ($this->saleInvoices as $invoiceHeader) {
            foreach ($invoiceHeader->saleReturnHeaders as $saleReturnHeader)
                $total -= $saleReturnHeader->grandTotal;
        }

        return $total;
    }

    public function searchWithPaging() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.cn_ordinal', $this->cn_ordinal);
        $criteria->compare('t.cn_month', $this->cn_month);
        $criteria->compare('t.cn_year', $this->cn_year);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('t.sale_header_id', $this->sale_header_id);
        $criteria->compare('t.branch_id', $this->branch_id);
        $criteria->compare('t.admin_id', $this->admin_id);
        $criteria->compare('t.is_non_tax', $this->is_non_tax);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->user->getState( 'pageSize', Yii::app()->params[ 'defaultPageSize' ] ),
			),
            'sort' => array(
                'defaultOrder' => 't.id DESC',
            ),
		));
    }

}
