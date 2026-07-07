<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 15% }
	.width1-3 { width: 50% }
	.width1-4 { width: 15% }

	.width2-1 { width: 20% }
	.width2-2 { width: 20% }
	.width2-3 { width: 20% }
	.width2-4 { width: 20% }
	.width2-5 { width: 20% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Hutang Pembelian</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Purchase Invoice #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Supplier</th>
		<th class="width1-4">Total</th>
	</tr>
	<tr id="header2">
		<td colspan="4">
			<table>
				<th class="width2-1">Tanda Terima #</th>
				<th class="width2-2">Tanggal</th>
				<th class="width2-3">Total</th>
				<th class="width2-4">Pelunasan</th>
				<th class="width2-5">Sisa</th>
			</table>	
		</td>
	</tr>
<?php foreach ($agingPayableSummary->dataProvider->data as $header): ?>
	<tr class="items1">
		<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(PurchaseInvoiceHeader::CN_CONSTANT)); ?></td>
		<td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
		<td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, 'supplier.company')); ?></td>
		<td class="width1-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($header, 'totalPurchase'))); ?></td>
	</tr>
	<tr class="items2">
		 <td colspan="4">
			<table>
				 <?php foreach ($header->purchaseReceiptDetails as $detail): ?>
					<tr>
						<td class="width2-1"><?php echo CHtml::encode($detail->purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)); ?></td>
						<td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->purchaseReceiptHeader->date))); ?></td>
						<td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'purchaseReceiptHeader.totalInvoice'))); ?></td>
						<td class="width2-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'purchaseReceiptHeader.payment'))); ?></td>
						<td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'purchaseReceiptHeader.remaining'))); ?></td>
					</tr>
				<?php endforeach; ?>	
			</table>	
		</td>
	</tr>	
<?php endforeach; ?>
<tr>
	<td class="width1-1" style="border-top: 1px solid"></td>
	<td class="width1-2" style="border-top: 1px solid"></td>
	<td class="width2-3" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL HUTANG</td>
	<td class="width2-4" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', floor($this->reportTotalPayable($agingPayableSummary->dataProvider)))); ?></td>
</tr>
</table>			
                                        
