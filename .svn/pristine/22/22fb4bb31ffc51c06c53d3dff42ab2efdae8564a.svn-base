<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 12% }
	.width1-2 { width: 12% }
	.width1-3 { width: 20% }
	.width1-4 { width: 12% }
	.width1-5 { width: 12% }
	.width1-6 { width: 12% }
	.width1-7 { width: 20% }
	
	.width2-1 { width: 35% }
	.width2-2 { width: 15% }
	.width2-3 { width: 10% }
	.width2-4 { width: 10% }
	.width2-5 { width: 15% }
	.width2-6 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan Penerimaan Belum Tanda Terima</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Penerimaan #</th>
		<th class="width1-2">Tanggal</th>
		<th class="width1-3">Supplier</th>
		<th class="width1-4">PO #</th>
		<th class="width1-5">PO Tanggal</th>
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
					<th class="width2-5">Harga Satuan</th>
					<th class="width2-6">Total</th>
				</tr>
			</table>
		</td>
	</tr>
    <?php $reportTotalQuantity = $reportGrandTotal = 0.00; ?>
	<?php foreach ($receiveOutstandingReceipt->dataProvider->data as $header): ?>
        <tr class="items1">
            <td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2" style="text-align: center">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?>
            </td>
            <td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, 'purchaseHeader.supplier.company')); ?></td>
            <td class="width1-4"><?php echo CHtml::encode($header->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
            <td class="width1-5">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->purchaseHeader->date))); ?>
            </td>
            <td class="width1-6"><?php echo CHtml::encode(CHtml::value($header, 'branch.name')); ?></td>
            <td class="width1-7"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
        </tr>
        <tr class="items2">
            <td colspan="7">  
                <table>
                    <?php 
                    $totalQuantity = $header->totalQuantity; 
                    $subTotal = 0.00; 
                    ?>
                    <?php foreach ($header->receiveDetails as $detail): ?>
                        <?php $totalPrice = $detail->totalReporting; ?>
                        <tr>
                            <td class="width2-1"><?php echo CHtml::encode(CHtml::value($detail, 'product.name')); ?></td>
                            <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?></td>
                            <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'quantity'))); ?></td>
                            <td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?></td>
                            <td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unitPrice'))); ?></td>
                            <td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $totalPrice)); ?></td>
                        </tr>
                        <?php $subTotal += $totalPrice; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold">Total</td>
                        <td style="text-align: right; border-top: 1px solid"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $totalQuantity)); ?></td>
                        <td colspan="3" style="text-align: right; font-weight: bold; border-top: 1px solid"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $subTotal)); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php 
        $reportTotalQuantity += $totalQuantity;
        $reportGrandTotal += $subTotal;
        ?>
    <?php endforeach; ?>
	<tr id="header1">
        <td colspan="2" style="text-align: right; font-weight: bold">Grand Total Quantity</td>
        <td style="text-align: right; font-weight: bold;">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $reportTotalQuantity)); ?>
        </td>
        <td colspan="3" style="text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $reportGrandTotal)); ?>
        </td>
	</tr>
</table>
