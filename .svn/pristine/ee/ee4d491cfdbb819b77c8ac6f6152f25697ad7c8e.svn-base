<?php
Yii::app()->clientScript->registerCss('_report', '
    .width1-1 { width: 15% }
    .width1-2 { width: 15% }
    .width1-3 { width: 40% }
    .width1-4 { width: 10% }
    .width1-5 { width: 15% }


    .width2-1 { width: 20% }
    .width2-2 { width: 15% }
    .width2-3 { width: 20% }
    .width2-4 { width: 20% }
    .width2-5 { width: 10% }
    .width2-6 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
    <div style="font-size: larger">Laporan Pembelian Barang</div>
    <div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
    <tr id="header1">
        <th class="width1-1">Pembelian #</th>
        <th class="width1-2">Tanggal</th>
        <th class="width1-3">Supplier</th>
        <th class="width1-4">Quantity</th>
        <th class="width1-5">Total</th>
    </tr>
    
    <tr id ="header2">
        <td colspan="5">
            <table>
                <tr>
                    <th class="width2-1">Penerimaan #</th>
                    <th class="width2-2">Tanggal</th>
                    <th class="width2-3">Faktur Pajak #</th>
                    <th class="width2-4">SJ #</th>
                    <th class="width2-5">Quantity</th>
                    <th class="width2-6">Total</th>
                </tr>
            </table>
        </td>
    </tr>
    
    <?php $grandTotalPurchase = 0.00; ?>
    <?php foreach ($purchaseRecapSummary->dataProvider->data as $header): ?>
        <?php if ($header->outstandingReceive > 1000.00): ?>
            <tr class="items1" style="color: red; font-weight: bold">
        <?php else: ?>
            <tr class="items1">
        <?php endif; ?>
            <td class="width1-1" style="text-align: center"><?php echo CHtml::encode($header->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
            <td class="width1-3" style="text-align: left"><?php echo CHtml::encode(CHtml::value($header, isset($header->supplier->company) ? 'supplier.company' : 'supplier.name')); ?></td>
            <td class="width1-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->totalQuantity)); ?></td>
            <td class="width1-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($header->grandTotal))); ?></td>
        </tr>
        <tr class="items2">
            <td colspan="5">
                <table>
                    <?php $totalReceiveValue = 0.00; ?>
                    <?php foreach ($header->receiveHeaders as $detail): ?>
                        <?php $totalPurchase = $detail->grandTotalReceipt; ?>
                        <tr>
                            <td class="width2-1" style="text-align: center"><?php echo CHtml::encode($detail->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?></td>
                            <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))); ?></td>
                            <td class="width2-3" style="text-align: center"><?php echo CHtml::encode($detail->supplier_tax_number); ?></td>
                            <td class="width2-4" style="text-align: right"><?php echo CHtml::encode(CHtml::value($detail, 'reference')); ?></td>
                            <td class="width2-5" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ($detail->totalQuantity))); ?></td>
                            <td class="width2-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', ($totalPurchase))); ?></td>
                        </tr>
                        <?php $totalReceiveValue += $totalPurchase; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" style="border-top: 0px solid;text-align: right;font-weight:bold">Total</td>
                        <td class="width2-6" style="border-top: 1px solid;text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $totalReceiveValue)); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php $grandTotalPurchase += $totalReceiveValue; ?>
    <?php endforeach; ?>
    <tr>
        <td colspan="4" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL PEMBELIAN</td>
        <td class="width1-5" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalPurchase)); ?></td>
    </tr>
</table>
