<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 15% }
	.width1-2 { width: 15% }
	.width1-3 { width: 35% }
	.width1-4 { width: 35% }
	
	.width2-1 { width: 17% }
	.width2-2 { width: 17% }
	.width2-3 { width: 17% }
	.width2-4 { width: 32% }
	.width2-5 { width: 17% }
');
?>

<div style="font-weight: bold; text-align: center">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
    <div style="font-size: larger">Laporan Tanda Terima Penjualan Detail</div>
    <div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
    <tr id="header1">
        <th class="width1-1">Tanda Terima #</th>
        <th class="width1-2">Tanggal</th>
        <th class="width1-3">Customer</th>
        <th class="width1-4">Catatan</th>
    </tr>
    
    <tr id="header2">
        <td colspan="4" style="border-bottom: 0px solid;">
            <table>
                <tr>
                    <th class="width2-1">Faktur #</th>
                    <th class="width2-2">Tanggal</th>
                    <th class="width2-3">Total(Rp)</th>
                    <th class="width2-5">PO #</th>
                    <th class="width2-4">Memo</th>
                </tr>
            </table>
        </td>
    </tr>
    
    <tr id="header2">
        <td colspan="4">
            <table>
                <tr>
                    <th class="width2-1">Pembayaran #</th>
                    <th class="width2-2">Tanggal</th>
                    <th class="width2-3">Total(Rp)</th>
                    <th colspan="2">Total Piutang</th>
                </tr>
            </table>
        </td>
    </tr>
    
    <?php $totalReceipt = 0.00;
    $grandTotalInvoice = 0.00;
    $grandTotalPayment = 0.00;
    $grandTotalCredit = 0.00; ?>
    
    <?php foreach ($saleReceiptSummary->dataProvider->data as $header): ?>
        <tr class="items1">
            <td class="width1-1" style="text-align: left"><?php echo CHtml::encode($header->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
            <td class="width1-3" style="text-align: center"><?php echo CHtml::encode(CHtml::value($header, 'customer.company')); ?></td>
            <td class="width1-4" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
        </tr>
        
        <tr class="items2">
            <?php if ($header->salePaymentHeaders != null) : ?>
                <td colspan="4" style="border-bottom: 0px solid;">
            <?php else: ?>
                <td colspan="4">
            <?php endif; ?>
                <table>
                    <?php foreach ($header->saleReceiptDetails as $detail): ?>
                        <tr>
                            <td class="width2-1" style="text-align: left"><?php echo CHtml::encode($detail->saleInvoice ? $detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT) : ''); ?></td>
                            <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->saleInvoice->date))); ?></td>
                            <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'saleInvoice.grandTotal'))); ?></td>
                            <td class="width2-5"><?php echo CHtml::encode($detail->saleInvoice ? $detail->saleInvoice->deliveryHeader->saleHeader->reference : ''); ?></td>
                            <td class="width2-4"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <tr>
                        <td colspan="2" style="border-top: 0px solid;text-align: right;font-weight:bold">Total</td>
                        <td class="width2-3" style="border-top: 1px solid;text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->totalInvoice)); ?></td>
                        <?php if ($header->salePaymentHeaders == null): ?>
                            <td class="width2-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->totalInvoice)); ?></td>
                        <?php else: ?>
                            <td class="width2-4"></td>
                        <?php endif; ?>
                    </tr>
                </table>
            </td>
        </tr>
        
        <?php if ($header->salePaymentHeaders != null): ?>
            <tr class="items2">
                <td colspan="4">
                    <table>
                        <?php $totalPayment = 0.00; ?>
                        <?php foreach ($header->salePaymentHeaders as $detail): ?>
                            <tr>
                                <td class="width2-1" style="text-align: left"><?php echo CHtml::encode($detail->getCodeNumber(SalePaymentHeader::CN_CONSTANT)); ?></td>
                                <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))); ?></td>
                                <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'amountPaid'))); ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <?php $totalPayment += $detail->amountPaid; ?>
                        <?php endforeach; ?>
                        <?php $totalCredit = $header->totalInvoice - $totalPayment; ?>
                        <tr>
                            <td colspan="2" style="border-top: 0px solid;text-align: right;font-weight:bold">Total</td>
                            <td class="width2-3" style="border-top: 1px solid;text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $totalPayment)); ?></td>
                            <td colspan="2" style="text-align: center;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $totalCredit)); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php $grandTotalPayment += $totalPayment; ?>
            <?php $grandTotalCredit += $totalCredit; ?>
        <?php else: ?>
            <?php $grandTotalCredit += $header->totalInvoice; ?> 
        <?php endif; ?>        
        <?php $totalReceipt += CHtml::value($header, 'totalInvoice'); ?>
        <?php $grandTotalInvoice += $header->totalInvoice; ?>
    <?php endforeach; ?>
            
    <tr id="header2">
        <td colspan="4" style="border-bottom: 0px solid">
            <table>      
                <tr>
                    <th class="width2-1" style=" border-bottom: 0px solid;"></th>
                    <th class="width2-2" style="text-align: right; border-bottom: 0px solid;">Grand Total Faktur</th>
                    <th class="width2-3" style="text-align: right; border-bottom: 0px solid;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalInvoice)); ?></th>
                    <th class="width2-4" style=" border-bottom: 0px solid;"></th>
                </tr>
                
                <tr>
                    <th class="width2-1" style=" border-bottom: 0px solid;"></th>
                    <th class="width2-2" style="text-align: right; border-bottom: 0px solid;">Grand Total Pembayaran</th>
                    <th class="width2-3" style="text-align: right; border-bottom: 0px solid;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalPayment)); ?></th>
                    <th class="width2-4" style=" border-bottom: 0px solid; text-align: right;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalCredit)); ?></th>
                </tr>
            </table>
        </td>
    </tr>
</table>
