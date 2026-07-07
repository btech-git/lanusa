<?php
Yii::app()->clientScript->registerCss('_report', '
    .width1-1 { width: 13% }
    .width1-2 { width: 10% }
    .width1-3 { width: 20% }
    .width1-4 { width: 15% }
    .width1-5 { width: 20% }
    .width1-6 { width: 10% }
    .width1-7 { width: 13% }

    .width2-1 { width: 15% }
    .width2-2 { width: 10% }
    .width2-3 { width: 10% }
    .width2-4 { width: 25% }
    .width2-5 { width: 15% }
    .width2-6 { width: 10% }
    .width2-7 { width: 15% }
');
?>

<div style="font-weight: bold; text-align: center">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
    <div style="font-size: larger">Laporan Penjualan Barang</div>
    <div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
    <tr id="header1">
        <th class="width1-1">Penjualan #</th>
        <th class="width1-2">Tanggal</th>
        <th class="width1-3">Customer</th>
        <th class="width1-4">PO #</th>
        <th class="width1-5">Catatan</th>
        <th class="width1-6">Quantity</th>
        <th class="width1-7">Grand Total</th>
    </tr>
    <tr id="header2">
        <td colspan="7">
            <table>
                <tr>
                    <th class="width2-1">Delivery #</th>
                    <th class="width2-2">Date</th>
                    <th class="width2-3">Quantity</th>
                    <th class="width2-4">Note</th>
                    <th class="width2-5">Invoice #</th>
                    <th class="width2-6">Date</th>
                    <th class="width2-7">Total Invoice</th>
                </tr>
            </table>
        </td>
    </tr>
    
    <?php foreach ($saleDeliveryInvoiceSummary->dataProvider->data as $header): ?>
        <?php if ($header->outstandingDelivery > 1000.00): ?>
            <tr class="items1" style="color: red; font-weight: bold">
        <?php else: ?>
            <tr class="items1">
        <?php endif; ?>
            <td class="width1-1"><?php echo CHtml::encode($header->getCodeNumber(SaleHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMM yyyy', strtotime($header->date))); ?></td>
            <td class="width1-3"><?php echo CHtml::encode(CHtml::value($header, isset($header->customer->company) ? 'customer.company' : 'customer.name')); ?></td>
            <td class="width1-4"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'reference'))); ?></td>
            <td class="width1-5"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
            <td class="width1-6" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $header->totalQuantity)); ?></td>
            <td class="width1-7" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->grandTotal)); ?></td>
        </tr>
        
        <tr class="items2">
            <td colspan="7">
                <table>
                    <?php foreach ($header->deliveryHeaders as $deliveryHeader): ?>
                        <?php if ((int) $deliveryHeader->is_inactive === 0) : ?>
                            <?php $saleInvoice = SaleInvoice::model()->findByAttributes(array('delivery_header_id' => $deliveryHeader->id)); ?>
                            <tr>
                                <td class="width2-1" style="text-align: center"><?php echo CHtml::encode($deliveryHeader->getCodeNumber(DeliveryHeader::CN_CONSTANT)); ?></td>
                                <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMM yyyy', strtotime(CHtml::value($deliveryHeader, 'date')))); ?></td>
                                <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ($deliveryHeader->totalQuantity))); ?></td>
                                <td class="width2-4" style="text-align: center"><?php echo CHtml::encode(CHtml::value($deliveryHeader, 'note')); ?></td>
                                <?php if (!empty($saleInvoice)): ?>
                                    <td class="width2-5" style="text-align: center"><?php echo CHtml::encode($saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?></td>
                                    <td class="width2-6" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMM yyyy', strtotime(CHtml::value($saleInvoice, 'date')))); ?></td>
                                    <td class="width2-7" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ceil($saleInvoice->grandTotal))); ?></td>
                                <?php else: ?>
                                    <td colspan="3"></td>
                                <?php endif; ?>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="6" style="text-align: right; border-top: 0px solid">TOTAL :</td>
                        <td class="width2-7" style="text-align: right; border-top: 1px solid"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', ($header->totalInvoiceReport))); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
