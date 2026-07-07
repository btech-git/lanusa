<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 20% }
	.width1-2 { width: 5% }
	.width1-3 { width: 5% }
	.width1-4 { width: 5% }
	.width1-5 { width: 5% }
	.width1-6 { width: 10% }
	.width1-7 { width: 10% }
	.width1-8 { width: 20% }
	.width1-9 { width: 10% }
	.width1-10 { width: 10% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Pengeluaran Giro Pembelian</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Pengeluaran Giro#</th>
		<th class="width1-2">Issue Date</th>
		<th class="width1-3">Due Date</th>
		<th class="width1-4">Nomor Giro #</th>
		<th class="width1-5">Catatan</th>
		<th class="width1-6">Bank</th>
		<th class="width1-7">Jumlah (Rp)</th>
		<th class="width1-8">TT Faktur#</th>
		<th class="width1-9">Tanggal TT</th>
		<th class="width1-10">Supplier</th>
	</tr>
        <tr id="header2">
			<td colspan="10"></td>
        </tr>
        
        <?php foreach ($purchaseChequeSummary->dataProvider->data as $header): ?>
			<tr class="items1">
				<td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(PurchaseCheque::CN_CONSTANT)); ?></td>
				<td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->issue_date))); ?></td>
				<td class="width1-3"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->due_date))); ?></td>
				<td class="width1-4"><?php echo CHtml::encode(CHtml::value($header, 'cheque_number')); ?></td>
				<td class="width1-5" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header,'note'));?></td>
				<td class="width1-6" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'account.name')); ?></td>
				<td class="width1-7" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($header, 'amount'))); ?></td>
				<td class="width1-8" style="text-align: right"><?php echo CHtml::encode($header->purchaseReceiptHeader->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)); ?></td>
				<td class="width1-9" style="text-align: right"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->purchaseReceiptHeader->date))); ?></td>
				<td class="width1-10" style="text-align: right"><?php echo CHtml::encode(CHtml::value($header, 'purchaseReceiptHeader.supplier.company')); ?></td>
			</tr>
			<tr class="items2">
				<td colspan="10"></td>
			</tr>
        <?php endforeach; ?>
</table>
