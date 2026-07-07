<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 12% }
	.width1-2 { width: 12% }
	.width1-3 { width: 12% }
	.width1-4 { width: 12% }
	.width1-5 { width: 18% }
	.width1-6 { width: 12% }
	.width1-7 { width: 10% }
	.width1-8 { width: 12% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Pajak Masukan</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Penerimaan #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Invoice #</th>
		<th class="width1-4">Faktur Pajak</th>
		<th class="width1-5">Supplier</th>
		<th class="width1-6">DPP</th>
		<th class="width1-7">PPN</th>
		<th class="width1-8">Total</th>
	</tr>
	<tr id ="header2">
		<td colspan="8"></td>
	</tr>
	<?php foreach ($purchaseRecapSummary->dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1" style="text-align: left">
                <?php echo CHtml::encode($header->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?>
            </td>
			<td class="width1-2" style="text-align: left">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?>
            </td>
			<td class="width1-3" style="text-align: left">
                <?php echo CHtml::encode(CHtml::value($header, 'reference')); ?>
            </td>
			<td class="width1-4" style="text-align: left">
                <?php echo CHtml::encode(CHtml::value($header, 'supplier_tax_number')); ?>
            </td>
			<td class="width1-5" style="text-align: left">
                <?php echo CHtml::encode(CHtml::value($header, isset($header->purchaseHeader->supplier->company) ? 'purchaseHeader.supplier.company' : 'purchaseHeader.supplier.name')); ?>
            </td>
			<td class="width1-6" style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->totalPurchase))); ?>
            </td>
			<td class="width1-7" style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->purchaseTax))); ?>
            </td>
			<td class="width1-8" style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->grandTotalReceipt))); ?>
            </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="5" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL</td>
        <td class="width1-6" style="border-top: 1px solid; font-weight: bold; text-align: right">
			<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $this->reportGrandTotalPurchase($purchaseRecapSummary->dataProvider))); ?>
		</td>
        <td class="width1-7" style="border-top: 1px solid; font-weight: bold; text-align: right">
			<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $this->reportGrandTotalTax($purchaseRecapSummary->dataProvider))); ?>
		</td>
		<td class="width1-8" style="border-top: 1px solid; font-weight: bold; text-align: right">
			<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $this->reportGrandTotal($purchaseRecapSummary->dataProvider))); ?>
		</td>
	</tr>
</table>
