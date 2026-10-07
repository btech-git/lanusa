<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 10% }
	.width1-2 { width: 7% }
	.width1-3 { width: 10% }
	.width1-4 { width: 10% }
	.width1-5 { width: 23% }
	.width1-6 { width: 10% }
	.width1-7 { width: 10% }
	.width1-8 { width: 10% }
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
        <th class="width1-1">PO #</th>
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
    <?php $subTotalSum = '0.00'; ?>
    <?php $taxAmountSum = '0.00'; ?>
    <?php $grandTotalSum = '0.00'; ?>
    
    <?php foreach ($purchaseRecapSummary->dataProvider->data as $header): ?>
        <?php $subTotal = CHtml::value($header, 'totalPurchase'); ?>
        <?php $taxAmount = CHtml::value($header, 'purchaseTax'); ?>
        <?php $grandTotal = CHtml::value($header, 'grandTotalReceipt'); ?>
    
        <tr class="items1">
            <td class="width1-1"><?php echo CHtml::encode($header->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->purchaseHeader->date))); ?>
            </td>
            <td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, 'reference')); ?></td>
            <td class="width1-4"><?php echo CHtml::encode(CHtml::value($header, 'supplier_tax_number')); ?></td>
            <td class="width1-5"><?php echo CHtml::encode(CHtml::value($header, 'purchaseHeader.supplier.company')); ?></td>
            <td class="width1-6" style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $subTotal)); ?>
            </td>
            <td class="width1-7" style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $taxAmount)); ?>
            </td>
            <td class="width1-8" style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotal)); ?>
            </td>
        </tr>
        
        <?php $subTotalSum += $subTotal; ?>
        <?php $taxAmountSum += $taxAmount; ?>
        <?php $grandTotalSum += $grandTotal; ?>
    <?php endforeach; ?>
    <tr>
        <td colspan="5" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL</td>
        <td class="width1-6" style="border-top: 1px solid; font-weight: bold; text-align: right">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $subTotalSum)); ?>
        </td>
        <td class="width1-7" style="border-top: 1px solid; font-weight: bold; text-align: right">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $taxAmountSum)); ?>
        </td>
        <td class="width1-8" style="border-top: 1px solid; font-weight: bold; text-align: right">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalSum)); ?>
        </td>
    </tr>
</table>
