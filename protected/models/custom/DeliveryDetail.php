<?php

class DeliveryDetail extends DeliveryDetailBase {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getProductName($saleHeaderId = null) {
        $saleDetail = SaleDetail::model()->findByAttributes(array(
            'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes' => 'resetScope'))->sale_header_id : $saleHeaderId,
            'product_id' => $this->product_id,
        ));

        return ($saleDetail === null) ? 0.00 : $saleDetail->product_name;
    }

    public function getProductUnit($saleHeaderId = null) {
        $saleDetail = SaleDetail::model()->findByAttributes(array(
            'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes' => 'resetScope'))->sale_header_id : $saleHeaderId,
            'product_id' => $this->product_id,
        ));

        return ($saleDetail === null) ? 0.00 : $saleDetail->unit->name;
    }

    public function getUnitPrice($saleHeaderId = null) {
        $saleDetail = SaleDetail::model()->findByAttributes(array(
            'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes' => 'resetScope'))->sale_header_id : $saleHeaderId,
            'product_id' => $this->product_id,
        ));

        return ($saleDetail === null) ? 0.00 : $saleDetail->unit_price;
    }

    public function getDiscountSale($saleHeaderId = null) {
        $saleDetail = SaleDetail::model()->findByAttributes(array(
            'sale_header_id' => ($saleHeaderId === null) ? $this->deliveryHeader(array('scopes' => 'resetScope'))->sale_header_id : $saleHeaderId,
            'product_id' => $this->product_id,
        ));

        return ($saleDetail === null) ? 0.00 : $saleDetail->discount;
    }

    public function getTotal($saleHeaderId = null) {
        return $this->quantity * $this->saleDetail->unit_price + $this->saleDetail->additional_fee_amount;
    }

    public function getQuantityOrdered($saleHeaderId = null) {
//		$sql = "SELECT sale.quantity - COALESCE(delivery.quantity_delivery, 0) AS quantity_sale
//				FROM
//				(
//					SELECT h.id, d.quantity, d.product_id
//					FROM ".  SaleHeader::model()->tableName()." h
//					INNER JOIN ".SaleDetail::model()->tableName()." d ON h.id = d.sale_header_id
//					WHERE h.is_inactive = 0 AND d.is_inactive = 0
//				) sale
//				LEFT OUTER JOIN
//				(
//					SELECT h.sale_header_id, SUM(COALESCE(d.quantity, 0)) AS quantity_delivery, d.product_id
//					FROM ".  DeliveryHeader::model()->tableName()." h
//					INNER JOIN ".DeliveryDetail::model()->tableName()." d ON h.id = d.delivery_header_id
//					WHERE h.is_inactive = 0 AND d.is_inactive = 0
//					GROUP BY h.sale_header_id, d.product_id
//				) delivery
//				ON sale.id = delivery.sale_header_id
//				AND sale.product_id = delivery.product_id
//				WHERE sale.id = :sale_id AND sale.product_id =:product_id 
//				AND sale.quantity - COALESCE(delivery.quantity_delivery, 0) > 0";

        $sql = "SELECT p.quantity - SUM(COALESCE(r.quantity, 0)) AS quantity_sale
				FROM " . SaleDetail::model()->tableName() . " p
				LEFT OUTER JOIN " . DeliveryDetail::model()->tableName() . " r
				ON p.id = r.sale_detail_id AND p.product_id = r.product_id AND r.is_inactive = 0 AND p.is_inactive = 0
				WHERE p.sale_header_id = :sale_header_id AND p.product_id = :product_id
				GROUP BY p.id
				HAVING quantity_sale > 0";

        $value = CActiveRecord::$db->createCommand($sql)->queryScalar(array(':sale_header_id' => $saleHeaderId, ':product_id' => $this->product_id));

        return ($value === false) ? 0 : $value;
    }
    
    public function getFastMovingProducts($startDate, $endDate) {
        
        $params = array(
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        );

        $sql = "SELECT d.product_id AS id, MAX(p.name) AS product_name, MAX(p.size) AS size, MAX(c.name) AS category, MAX(u.name) AS unit_name, 
                    COALESCE(SUM(d.quantity), 0) AS total_sale, COALESCE(SUM(d.quantity * s.unit_price), 0) AS sale_price
                FROM " . DeliveryDetail::model()->tableName() . " d
                INNER JOIN " . DeliveryHeader::model()->tableName() . " h ON h.id = d.delivery_header_id
                INNER JOIN " . SaleInvoice::model()->tableName() . " i ON h.id = i.delivery_header_id
                INNER JOIN " . SaleDetail::model()->tableName() . " s ON s.id = d.sale_detail_id
                INNER JOIN " . Product::model()->tableName() . " p ON p.id = d.product_id
                INNER JOIN " . Category::model()->tableName() . " c ON c.id = p.category_id
                INNER JOIN " . Unit::model()->tableName() . " u ON u.id = p.unit_id
                WHERE i.date BETWEEN :start_date AND :end_date AND i.is_inactive = 0
                GROUP BY d.product_id
                HAVING sale_price > 0
                ORDER BY total_sale DESC
                LIMIT 500";

        $resultSet = Yii::app()->db->createCommand($sql)->queryAll(true, $params);

        return $resultSet;
    }
}