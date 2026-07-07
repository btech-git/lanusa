<?php

class SqlViewGenerator extends CComponent
{
	public static function count($view)
	{
		$sql = "SELECT COUNT(*) FROM ({$view}) v";

		return $sql;
	}

	public static function receiveStock()
	{
		$sql = "SELECT COALESCE(SUM(d.quantity), 0) AS quantity 
				FROM ".ReceiveHeader::model()->tableName()." h 
				INNER JOIN ".ReceiveDetail::model()->tableName()." d ON h.id = d.receive_header_id";

		return $sql;
	}

	public static function purchaseReturnStock()
	{
		$sql = "SELECT COALESCE(SUM(d.quantity), 0) AS quantity 
				FROM ".PurchaseReturnHeader::model()->tableName()." h 
				INNER JOIN ".PurchaseReturnDetail::model()->tableName()." d ON h.id = d.purchase_return_header_id";

		return $sql;
	}

	public static function adjustmentStock()
	{
		$sql = "SELECT COALESCE(SUM(d.quantity_adjustment - d.quantity_current), 0) AS quantity 
				FROM ".AdjustmentHeader::model()->tableName()." h 
				INNER JOIN ".AdjustmentDetail::model()->tableName()." d ON h.id = d.adjustment_header_id";

		return $sql;
	}

	public static function transferStock()
	{
		$sql = "SELECT COALESCE(SUM(d.quantity), 0) AS quantity 
				FROM ".TransferHeader::model()->tableName()." h 
				INNER JOIN ".TransferDetail::model()->tableName()." d ON h.id = d.transfer_header_id";

		return $sql;
	}

	public static function deliveryStock()
	{
		$sql = "SELECT COALESCE(SUM(d.quantity), 0) AS quantity 
				FROM ".DeliveryHeader::model()->tableName()." h 
				INNER JOIN ".DeliveryDetail::model()->tableName()." d ON h.id = d.delivery_header_id";

		return $sql;
	}

	public static function saleReturnStock()
	{
		$sql = "SELECT COALESCE(SUM(d.quantity), 0) AS quantity 
				FROM ".SaleReturnHeader::model()->tableName()." h 
				INNER JOIN ".SaleReturnDetail::model()->tableName()." d ON h.id = d.sale_return_header_id";

		return $sql;
	}

	public static function globalStock()
	{
		$sql = "SELECT p.id, COALESCE(receive.quantity, 0) - COALESCE(purchase_return.quantity, 0) + COALESCE(adjustment.quantity, 0) - COALESCE(transfer_from.quantity, 0) + COALESCE(transfer_to.quantity, 0) - COALESCE(delivery.quantity, 0) + COALESCE(sale_return.quantity, 0) AS current_stock
				FROM tblla_product p
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity), 0) AS quantity 
					FROM tblla_receive_header h 
					INNER JOIN tblla_receive_detail d ON h.id = d.receive_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) receive
				ON p.id = receive.product_id
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity), 0) AS quantity 
					FROM tblla_purchase_return_header h 
					INNER JOIN tblla_purchase_return_detail d ON h.id = d.purchase_return_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) purchase_return
				ON p.id = purchase_return.product_id
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity_adjustment - d.quantity_current), 0) AS quantity 
					FROM tblla_adjustment_header h 
					INNER JOIN tblla_adjustment_detail d ON h.id = d.adjustment_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) adjustment
				ON p.id = adjustment.product_id
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity), 0) AS quantity 
					FROM tblla_transfer_header h 
					INNER JOIN tblla_transfer_detail d ON h.id = d.transfer_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) transfer_from
				ON p.id = transfer_from.product_id
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity), 0) AS quantity 
					FROM tblla_transfer_header h 
					INNER JOIN tblla_transfer_detail d ON h.id = d.transfer_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) transfer_to
				ON p.id = transfer_to.product_id
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity), 0) AS quantity 
					FROM tblla_delivery_header h 
					INNER JOIN tblla_delivery_detail d ON h.id = d.delivery_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) delivery
				ON p.id = delivery.product_id
				LEFT OUTER JOIN
				(
					SELECT d.product_id, COALESCE(SUM(d.quantity), 0) AS quantity 
					FROM tblla_sale_return_header h 
					INNER JOIN tblla_sale_return_detail d ON h.id = d.sale_return_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY d.product_id
				) sale_return
				ON p.id = sale_return.product_id";

		return $sql;
	}

	public static function excelCategorySize()
	{
		$sql = "SELECT DISTINCT category_id, size FROM tblla_product 
				WHERE is_inactive = 0 ORDER BY category_id, size";

		return $sql;
	}

	public static function excelGlobalStock()
	{
		$sql = "SELECT p.category_id, p.name, p.size, c.name AS category_name, ps.current_stock
				FROM tblla_product p INNER JOIN tblla_category c ON p.category_id = c.id
				INNER JOIN (".SqlViewGenerator::globalStock().") ps
				ON p.id = ps.id
				WHERE p.is_inactive = 0 AND c.is_inactive = 0
				ORDER BY p.category_id, p.name, p.size";

		return $sql;
	}
	
	public static function quantityPurchase()
	{
		//original version without selecting purchase detail id
//		$sql = "SELECT purchase.quantity - COALESCE(receive.quantity, 0) AS quantity_ordered, purchase.product_id
//				FROM
//				(
//					SELECT ph.id, pd.quantity, pd.product_id
//					FROM tblla_purchase_header ph
//					INNER JOIN tblla_purchase_detail pd ON ph.id = pd.purchase_header_id
//					WHERE ph.is_inactive = 0 AND pd.is_inactive = 0
//				) purchase
//				LEFT OUTER JOIN
//				(
//					SELECT rh.purchase_header_id, SUM(COALESCE(rd.quantity, 0)) AS quantity, rd.product_id
//					FROM tblla_receive_header rh
//					INNER JOIN tblla_receive_detail rd ON rh.id = rd.receive_header_id
//					WHERE rh.is_inactive = 0 AND rd.is_inactive = 0
//					GROUP BY rh.purchase_header_id, rd.product_id
//				) receive
//				ON purchase.id = receive.purchase_header_id
//				AND purchase.product_id = receive.product_id";
		
		$sql = "SELECT purchase.quantity - COALESCE(receive.quantity, 0) AS quantity_ordered, purchase.product_id, purchase.purchase_detail_id
				FROM
				(
					SELECT ph.id, pd.quantity, pd.product_id, pd.id AS purchase_detail_id
					FROM tblla_purchase_header ph
					INNER JOIN tblla_purchase_detail pd ON ph.id = pd.purchase_header_id
                                        INNER JOIN tblla_product pr on pr.id = pd.product_id
					WHERE ph.is_inactive = 0 AND pd.is_inactive = 0 AND pr.is_inactive=0
				) purchase
				LEFT OUTER JOIN
				(
					SELECT rh.purchase_header_id, SUM(COALESCE(rd.quantity, 0)) AS quantity, rd.product_id
					FROM tblla_receive_header rh
					INNER JOIN tblla_receive_detail rd ON rh.id = rd.receive_header_id
					WHERE rh.is_inactive = 0 AND rd.is_inactive = 0
					GROUP BY rh.purchase_header_id, rd.product_id
				) receive
                                
				ON purchase.id = receive.purchase_header_id
				AND purchase.product_id = receive.product_id";

		return $sql;
	}
	
	public static function quantitySale()
	{
		$sql = "SELECT sale.quantity - COALESCE(delivery.quantity, 0) AS quantity_ordered, sale.product_id
				FROM
				(
					SELECT ph.id, pd.quantity, pd.product_id
					FROM tblla_sale_header ph
					INNER JOIN tblla_sale_detail pd ON ph.id = pd.sale_header_id
					WHERE ph.is_inactive = 0 AND pd.is_inactive = 0
				) sale
				LEFT OUTER JOIN
				(
					SELECT rh.sale_header_id, SUM(COALESCE(rd.quantity, 0)) AS quantity, rd.product_id
					FROM tblla_delivery_header rh
					INNER JOIN tblla_delivery_detail rd ON rh.id = rd.delivery_header_id
					WHERE rh.is_inactive = 0 AND rd.is_inactive = 0
					GROUP BY rh.sale_header_id, rd.product_id
				) delivery
				ON sale.id = delivery.sale_header_id
				AND sale.product_id = delivery.product_id";

		return $sql;
	}
	
	public static function quantityReceive()
	{
		$sql = "SELECT receive.quantity - COALESCE(returned.quantity, 0) AS quantity_received, receive.product_id
				FROM 
				(
					SELECT h.id, d.quantity, d.product_id
					FROM " . ReceiveHeader::model()->tableName() . " h 
					INNER JOIN " . ReceiveDetail::model()->tableName() . " d ON h.id = d.receive_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
				) receive
				LEFT OUTER JOIN
				(
					SELECT h.receive_header_id, SUM(d.quantity) AS quantity, d.product_id
					FROM " . PurchaseReturnHeader::model()->tableName() . " h
					INNER JOIN " . PurchaseReturnDetail::model()->tableName() . " d ON h.id = d.purchase_return_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY h.receive_header_id, d.product_id
				) returned
				ON receive.id = returned.receive_header_id
				AND receive.product_id = returned.product_id";

		return $sql;
	}
	
	public static function quantityDelivery()
	{
		$sql = "
			SELECT delivery.quantity - COALESCE(returned.quantity, 0) AS quantity_returned, delivery.product_id
			FROM
			(
				SELECT h.id, d.quantity, d.product_id
				FROM tblla_delivery_header h
				INNER JOIN tblla_delivery_detail d ON h.id = d.delivery_header_id
				WHERE h.is_inactive = 0 AND d.is_inactive = 0
			) delivery
			LEFT OUTER JOIN
			(
				SELECT h.id, h.delivery_header_id
				FROM tblla_sale_invoice h 
				WHERE h.is_inactive = 0 
			) invoice
			ON delivery.id = invoice.delivery_header_id
			LEFT OUTER JOIN
			(
				SELECT rh.sale_invoice_id, SUM(rd.quantity) AS quantity, rd.product_id
				FROM tblla_sale_return_header rh
				INNER JOIN tblla_sale_return_detail rd ON rh.id = rd.sale_return_header_id
				WHERE rh.is_inactive = 0 AND rd.is_inactive = 0
				GROUP BY rh.sale_invoice_id, rd.product_id
			) returned
			ON invoice.id = returned.sale_invoice_id
			AND delivery.product_id = returned.product_id";

		return $sql;
	}

	public static function balance()
	{
		$sql = "SELECT h.date, h.branch_id, h.account_id, d.account_id AS detail_account_id, d.amount AS debit, 0 AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . DepositHeader::model()->tableName() . " h 
				INNER JOIN " . DepositDetail::model()->tableName() . " d ON h.id = d.deposit_header_id
				WHERE h.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT h.date, h.branch_id, h.account_id, d.account_id AS detail_account_id, 0 AS debit, d.amount AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . ExpenseHeader::model()->tableName() . " h 
				INNER JOIN " . ExpenseDetail::model()->tableName() . " d ON h.id = d.expense_header_id
				WHERE h.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT h.date, h.branch_id, d.account_id, h.account_id AS detail_account_id, 0 AS debit, d.amount AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . DepositHeader::model()->tableName() . " h 
				INNER JOIN " . DepositDetail::model()->tableName() . " d ON h.id = d.deposit_header_id
				WHERE d.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT h.date, h.branch_id, d.account_id, h.account_id AS detail_account_id, d.amount AS debit, 0 AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . ExpenseHeader::model()->tableName() . " h 
				INNER JOIN " . ExpenseDetail::model()->tableName() . " d ON h.id = d.expense_header_id
				WHERE d.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT h.date, h.branch_id, d.account_id, d.account_id AS detail_account_id, d.debit AS debit, d.credit AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . JournalVoucherHeader::model()->tableName() . " h 
				INNER JOIN " . JournalVoucherDetail::model()->tableName() . " d ON h.id = d.journal_voucher_header_id
				WHERE d.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT h.date, h.branch_id, d.account_id, s.account_id AS detail_account_id, 0 AS debit, d.amount AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . PurchasePaymentHeader::model()->tableName() . " h 
				INNER JOIN " . PurchasePaymentDetail::model()->tableName() . " d ON h.id = d.purchase_payment_header_id
				INNER JOIN " . PurchaseReceiptHeader::model()->tableName() . "  pr ON pr.id = h.purchase_receipt_header_id
				INNER JOIN " . Supplier::model()->tableName() . " s ON s.id = pr.supplier_id 
				WHERE d.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT h.date, h.branch_id, d.account_id, c.account_id AS detail_account_id, d.amount AS debit, 0 AS credit, d.memo AS note, d.is_inactive, d.id as module
				FROM " . SalePaymentHeader::model()->tableName() . " h 
				INNER JOIN " . SalePaymentDetail::model()->tableName() . " d ON h.id = d.sale_payment_header_id
				INNER JOIN " .SaleReceiptHeader::model()->tableName() . " r ON r.id = h.sale_receipt_header_id
				INNER JOIN " . Customer::model()->tableName() . " c ON c.id = r.customer_id
				WHERE d.account_id = :account_id AND h.is_inactive = 0 AND d.is_inactive = 0
				UNION ALL
				SELECT date, sd.branch_id, sd.account_id, c.account_id AS detail_account_id, amount AS debit, 0 AS credit, sd.note AS note, sd.is_inactive, sd.id as module
				FROM " . SaleDownpayment::model()->tableName() . " sd	
				INNER JOIN " . Customer::model()->tableName() . " c ON c.id = sd.customer_id
				WHERE sd.account_id = :account_id AND sd.is_inactive = 0";
		
		return $sql;
	}
	
	public static function salePaymentRemaining()
	{
		$sql = "EXISTS (
				SELECT receipt.total_sale - COALESCE(payment.amount, 0) AS remaining FROM
				(
					SELECT receipt_detail.sale_receipt_header_id AS id, SUM(sale_header.total) AS total_sale 
					FROM ". SaleReceiptDetail::model()->tableName() ." AS receipt_detail 
					INNER JOIN 
					(
						SELECT sale.invoice_id, ((sale.quantity - COALESCE(returned.quantity_returned, 0)) * sale.unit_price * sale.discount_detail * sale.discount_header - sale.downpayment) * sale.tax + sale.shipping AS total 
						FROM
						(
							SELECT si.id AS invoice_id, d.product_id, d.quantity, d.unit_price, (1 - (d.discount/100)) AS discount_detail, (1 - (h.discount/100)) AS discount_header, COALESCE(sd.amount, 0) AS downpayment, (1 + (h.tax/100)) AS tax, h.shipping_fee AS shipping
							FROM ". SaleHeader::model()->tableName() ." h 
							INNER JOIN ". SaleDetail::model()->tableName() ." d ON h.id = d.sale_header_id
							LEFT OUTER JOIN ". SaleDownpayment::model()->tableName() ." sd ON sd.id = h.sale_downpayment_id
							LEFT OUTER JOIN ". DeliveryHeader::model()->tableName() ." dh ON h.id = dh.sale_header_id
							LEFT OUTER JOIN ". SaleInvoice::model()->tableName() ." si ON dh.id = si.delivery_header_id
							WHERE h.is_inactive = 0 AND d.is_inactive = 0 AND sd.is_inactive = 0 AND dh.is_inactive = 0 AND si.is_inactive = 0
						) sale
						LEFT OUTER JOIN
						(
							SELECT h.sale_invoice_id, d.product_id, SUM(d.quantity) AS quantity_returned, 1 + (h.tax / 100) AS tax, SUM(h.shipping_fee) AS shipping_fee
							FROM ". SaleReturnHeader::model()->tableName() ." h 
							INNER JOIN ". SaleReturnDetail::model()->tableName() ." d ON h.id = d.sale_return_header_id
							WHERE h.is_inactive = 0 AND d.is_inactive = 0
							GROUP BY h.sale_invoice_id, d.product_id
						) returned
						ON sale.invoice_id = returned.sale_invoice_id AND sale.product_id = returned.product_id
						GROUP BY sale.invoice_id
					) sale_header
					ON receipt_detail.sale_invoice_id = sale_header.invoice_id
					GROUP BY id
				) receipt
				LEFT OUTER JOIN
				(
					SELECT h.sale_receipt_header_id, SUM(d.amount) AS amount 
					FROM ". SalePaymentHeader::model()->tableName() ." h 
					INNER JOIN ". SalePaymentDetail::model()->tableName() ." d ON h.id = d.sale_payment_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY h.sale_receipt_header_id
				) payment
				ON receipt.id = payment.sale_receipt_header_id
				WHERE t.id = receipt.id
				HAVING remaining > 0
			)";
		
		return $sql;
	}
	
//	public static function purchasePaymentRemaining()
//	{
//		$sql = "EXISTS (
//				SELECT receipt.total_purchase - COALESCE(payment.amount, 0) AS remaining FROM
//				(
//					SELECT receipt_detail.purchase_receipt_header_id AS id, SUM(purchasing.total) AS total_purchase 
//					FROM ". PurchaseReceiptDetail::model()->tableName() ." AS receipt_detail INNER JOIN 
//					(
//						SELECT purchase.id, invoice.id AS invoice_id, ((SUM(purchase.quantity  * purchase.unit_price * purchase.discount_detail) * purchase.discount_header) * purchase.tax + purchase.shipping) AS total 
//						FROM
//						(
//							SELECT h.id, d.product_id, d.quantity, d.unit_price, (1 - (d.discount/100)) AS discount_detail, (1 - (h.discount/100)) AS discount_header, (1 + (h.tax/100)) AS tax, h.shipping_fee AS shipping
//							FROM ". PurchaseHeader::model()->tableName() ." h 
//							INNER JOIN ". PurchaseDetail::model()->tableName() ." d ON h.id = d.purchase_header_id
//							WHERE h.is_inactive = 0 AND d.is_inactive = 0
//						) purchase
//						LEFT OUTER JOIN
//						(
//							SELECT h.id AS id, d.purchase_header_id AS purchase_header_id
//							FROM ". PurchaseInvoiceHeader::model()->tableName() ." h
//							INNER JOIN ". PurchaseInvoiceDetail::model()->tableName() . " d ON h.id = d.purchase_invoice_header_id
//							WHERE h.is_inactive = 0 AND d.is_inactive = 0
//						) invoice
//						ON purchase.id = invoice.purchase_header_id
//						GROUP BY purchase.id, invoice_id
//					) purchasing
//					ON receipt_detail.purchase_invoice_header_id = purchasing.invoice_id
//					GROUP BY id
//				) receipt
//				LEFT OUTER JOIN
//				(
//					SELECT h.purchase_receipt_header_id, COALESCE(SUM(d.amount), 0) AS amount 
//					FROM ". PurchasePaymentHeader::model()->tableName() ." h 
//					INNER JOIN ". PurchasePaymentDetail::model()->tableName() ." d ON h.id = d.purchase_payment_header_id
//					WHERE h.is_inactive = 0 AND d.is_inactive = 0
//					GROUP BY h.purchase_receipt_header_id
//				) payment
//				ON receipt.id = payment.purchase_receipt_header_id
//				WHERE t.id = receipt.id
//				HAVING remaining > 0
//			)";
//		
//		return $sql;
//	}
	
	public static function agingReceivable()
	{
		$sql = "EXISTS (
				SELECT receipt.total_sale - COALESCE(payment.amount, 0) AS remaining FROM
				(
					SELECT receipt_detail.receipt_header_id AS id, SUM(sale.total) AS total_sale 
					FROM ". ReceiptDetail::model()->tableName() ." AS receipt_detail INNER JOIN 
					(
						SELECT delivery.id, invoice.id AS invoice_id, (SUM((delivery.quantity - COALESCE(returned.quantity_returned, 0)) * delivery.unit_price * delivery.discount_detail) * delivery.discount_header - delivery.downpayment) * delivery.tax + delivery.shipping AS total FROM
						(
							SELECT h.id, d.product_id, d.quantity, d.unit_price, (1 - (d.discount/100)) AS discount_detail, (1 - (h.discount/100)) AS discount_header, COALESCE(sd.amount, 0) AS downpayment, (1 + (h.tax/100)) AS tax, h.shipping_fee AS shipping
							FROM ". DeliveryHeader::model()->tableName() ." h 
							INNER JOIN ". DeliveryDetail::model()->tableName() ." d ON h.id = d.delivery_header_id
							LEFT OUTER JOIN ". SaleDownpayment::model()->tableName() ." sd ON sd.id = h.sale_downpayment_id
							WHERE h.is_inactive = 0 AND d.is_inactive = 0
						) delivery
						LEFT OUTER JOIN
						(
							SELECT id, delivery_header_id
							FROM ". InvoiceHeader::model()->tableName() ."
							WHERE is_inactive = 0
						) invoice
						ON delivery.id = invoice.delivery_header_id
						LEFT OUTER JOIN
						(
							SELECT h.invoice_header_id, d.product_id, SUM(d.quantity) AS quantity_returned 
							FROM ". SaleReturnHeader::model()->tableName() ." h 
							INNER JOIN ". SaleReturnDetail::model()->tableName() ." d ON h.id = d.sale_return_header_id
							WHERE h.is_inactive = 0 AND d.is_inactive = 0
							GROUP BY h.invoice_header_id, d.product_id
						) returned
						ON invoice.id = returned.invoice_header_id AND delivery.product_id = returned.product_id
						GROUP BY delivery.id, invoice_id
					) sale
					ON receipt_detail.invoice_header_id = sale.invoice_id
					GROUP BY id
				) receipt
				LEFT OUTER JOIN
				(
					SELECT h.receipt_header_id, COALESCE(SUM(d.amount), 0) AS amount 
					FROM ". SalePaymentHeader::model()->tableName() ." h 
					INNER JOIN ". SalePaymentDetail::model()->tableName() ." d ON h.id = d.sale_payment_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY h.receipt_header_id
				) payment
				ON receipt.id = payment.receipt_header_id
				WHERE receiptDetail.receipt_header_id = receipt.id
				HAVING remaining > 0
			) OR NOT EXISTS (SELECT rd.id FROM tblla_receipt_detail rd WHERE t.id = rd.invoice_header_id)";
		
		return $sql;
	}
	
	public static function agingPayable()
	{
		$sql = "EXISTS (
				SELECT receipt.total_purchase - COALESCE(payment.amount, 0) AS remaining FROM
				(
					SELECT receipt_detail.purchase_receipt_header_id AS id, SUM(purchasing.total) AS total_purchase 
					FROM ". PurchaseReceiptDetail::model()->tableName() ." AS receipt_detail INNER JOIN 
					(
						SELECT purchase.id, invoice.id AS invoice_id, (SUM((purchase.quantity - COALESCE(returned.quantity_returned, 0)) * purchase.unit_price * purchase.discount_detail) * purchase.discount_header) * purchase.tax + purchase.shipping AS total FROM
						(
							SELECT h.id, d.product_id, d.quantity, d.unit_price, (1 - (d.discount/100)) AS discount_detail, (1 - (h.discount/100)) AS discount_header, (1 + (h.tax/100)) AS tax, h.shipping_fee AS shipping
							FROM ". PurchaseHeader::model()->tableName() ." h 
							INNER JOIN ". PurchaseDetail::model()->tableName() ." d ON h.id = d.purchase_header_id
							WHERE h.is_inactive = 0 AND d.is_inactive = 0
						) purchase
						LEFT OUTER JOIN
						(
							SELECT id, purchase_header_id
							FROM ". PurchaseInvoice::model()->tableName() ."
							WHERE is_inactive = 0
						) invoice
						ON purchase.id = invoice.purchase_header_id
						LEFT OUTER JOIN
						(
							SELECT h.purchase_invoice_id, d.product_id, SUM(d.quantity) AS quantity_returned 
							FROM ". PurchaseReturnHeader::model()->tableName() ." h 
							INNER JOIN ". PurchaseReturnDetail::model()->tableName() ." d ON h.id = d.purchase_return_header_id
							WHERE h.is_inactive = 0 AND d.is_inactive = 0
							GROUP BY h.purchase_invoice_id, d.product_id
						) returned
						ON invoice.id = returned.purchase_invoice_id AND purchase.product_id = returned.product_id
						GROUP BY purchase.id, invoice_id
					) purchasing
					ON receipt_detail.purchase_invoice_id = purchasing.invoice_id
					GROUP BY id
				) receipt
				LEFT OUTER JOIN
				(
					SELECT h.purchase_receipt_header_id, COALESCE(SUM(d.amount), 0) AS amount 
					FROM ". PurchasePaymentHeader::model()->tableName() ." h 
					INNER JOIN ". PurchasePaymentDetail::model()->tableName() ." d ON h.id = d.purchase_payment_header_id
					WHERE h.is_inactive = 0 AND d.is_inactive = 0
					GROUP BY h.purchase_receipt_header_id
				) payment
				ON receipt.id = payment.purchase_receipt_header_id
				WHERE t.id = receipt.id
				HAVING remaining > 0
			) OR NOT EXISTS (SELECT rd.id FROM tblla_purchase_receipt_detail rd WHERE t.id = rd.purchase_invoice_id)";
		
		return $sql;
	}
	
	public static function cogs()
	{
		$sql = "SELECT SUM(quantity * unit_price * (1 - (discount / 100))) / SUM(quantity) AS cogs
				FROM tblla_purchase_detail";
		
		return $sql;
	}
}
