<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 12% }
	.width1-2 { width: 12% }
	.width1-3 { width: 20% }
	.width1-4 { width: 12% }
	.width1-5 { width: 12% }
	.width1-6 { width: 12% }
	.width1-7 { width: 20% }
	
	.width2-1 { width: 30% }
	.width2-2 { width: 20% }
	.width2-3 { width: 10% }
	.width2-4 { width: 10% }
	.width2-5 { width: 30% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan SJ Belum Invoice</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">SJ #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Customer</th>
		<th class="width1-4">Order #</th>
		<th class="width1-5">PO Customer #</th>
		<th class="width1-6">Cabang</th>
		<th class="width1-7">Catatan</th>
	</tr>
	<tr id="header2">
		<td colspan="7">
			<table>
				<tr>
					<th class="width2-1">Nama Barang</th>
					<th class="width2-2">Ukuran</th>
					<th class="width2-3">Qty</th>
					<th class="width2-4">Satuan</th>
					<th class="width2-5">Gudang</th>
				</tr>
			</table>
		</td>
	</tr>
    <?php $totalQuantity = 0.00; ?>
	<?php foreach ($deliveryOutstandingInvoice->dataProvider->data as $header): ?>
        <tr class="items1">
            <td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2" style="text-align: center">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?>
            </td>
            <td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, 'saleHeader.customer.company')); ?></td>
            <td class="width1-4"><?php echo CHtml::encode($header->saleHeader->getCodeNumber(SaleHeader::CN_CONSTANT)); ?></td>
            <td class="width1-5"><?php echo CHtml::encode(CHtml::value($header, 'saleHeader.reference')); ?></td>
            <td class="width1-6"><?php echo CHtml::encode(CHtml::value($header, 'branch.name')); ?></td>
            <td class="width1-7"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
        </tr>
        <tr class="items2">
            <td colspan="7">  
                <table>
                    <?php foreach ($header->deliveryDetails as $detail): ?>
                        <tr>
                            <td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
                            <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
                            <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantity'))); ?></td>
                            <td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
                            <td class="width2-5"><?php echo CHtml::encode(CHtml::value($detail, 'warehouse.name')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" style="border-top: 0px solid;text-align: right;font-weight:bold">Total QTY</td>
                        <td class="width2-3" style="border-top: 1px solid;text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->totalQuantity)); ?></td>
                        <td colspan="2" >&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php $totalQuantity += CHtml::value($header, 'totalQuantity'); ?>
    <?php endforeach; ?>
	<tr id="header2">
		<td colspan="4" style="border-bottom: 0px solid">
			<table>      
				<tr>
					<th colspan="2" style="text-align: right; border-bottom: 0px solid;">Grand Total Quantity</th>
					<th class="width2-3" style="text-align: right; border-bottom: 0px solid;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $totalQuantity)); ?></th>
                    <th colspan="2" style=" border-bottom: 0px solid;">&nbsp;</th>
				</tr>
			</table>
		</td>
	</tr>
</table>
