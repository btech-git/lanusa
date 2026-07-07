<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center; width: 15%">Nomor Faktur</th>
		<th style="text-align: center; width: 15%">Tanggal</th>
		<th style="text-align: center; width: 20%">Supplier</th>
		<th style="text-align: center; width: 15%">Total</th>
		<th style="text-align: center; width: 30%">Memo</th>
	</tr>
	<?php foreach ($purchaseReceipt->purchaseReceiptDetails as $i=>$detail): ?>
	
		<tr style="background-color: azure">
			<td>
				<?php echo CHtml::activeHiddenField($detail, "[$i]purchase_invoice_header_id"); ?>
				<?php echo CHtml::encode($detail->purchaseInvoiceHeader->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT)); ?>
			</td>
			<td>
				<?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail->purchaseInvoiceHeader, 'date')))); ?>
			</td>
			<td>
				<?php echo CHtml::encode(CHtml::value($detail->purchaseInvoiceHeader, 'purchaseHeader.supplier.company')); ?>
			</td>
			<td style="text-align: right">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail->purchaseInvoiceHeader, 'totalPurchase'))); ?>
			</td>
			<td style="text-align: center">
			<?php echo CHtml::activeTextField($detail, "[$i]memo", array('size'=>30, 'maxlength'=>60)); ?>
			<?php echo CHtml::error($detail, 'memo'); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr style="background-color: aquamarine">
		<td colspan="2"></td>
		<td style="font-weight: bold">TOTAL</td>
		<td style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReceipt->totalInvoice)); ?></td>
		<td></td>
	</tr>
</table>
