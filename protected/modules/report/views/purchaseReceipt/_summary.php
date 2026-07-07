<?php
Yii::app()->clientScript->registerCss('_report', '
	.width1-1 { width: 15% }
	.width1-2 { width: 15% }
	.width1-3 { width: 30% }
	.width1-4 { width: 40% }


	.width2-1 { width: 20% }
	.width2-2 { width: 20% }
	.width2-3 { width: 20% }
	.width2-4 { width: 40% }
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/memo.css');
Yii::app()->clientScript->registerCss('memo', '
    .hcolumn1 { width: 50% }
    .hcolumn2 { width: 50% }

    .hcolumn1header { width: 55% }
    .hcolumn1value { width: 45% }
    .hcolumn2header { width: 35% }
    .hcolumn2value { width: 65% }

    .sig1 { width: 25% }
    .sig2 { width: 50% }
    .sig3 { width: 25% }
');
?>

<div style="font-weight: bold; text-align: center">
    <div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
    <div style="font-size: larger">Laporan Hutang Detail</div>
    <div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
    <tr id="header1">
        <th class="width1-1">Tanda Terima Pembelian #</th>
        <th class="width1-2">Tanggal</th>
        <th class="width1-3">Supplier</th>
        <th class="width1-4">Catatan</th>
    </tr>
    <tr id="header2">
        <td colspan="4" style="border-bottom: 0px solid;">
            <table>
                <tr>
                    <th class="width2-1">PO #</th>
                    <th class="width2-2">Tanggal</th>
                    <th class="width2-3">Total</th>
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
                    <th class="width2-3">Total</th>
                    <th class="width2-4" style="text-align: right;">Total Hutang</th>
                </tr>
            </table>
        </td>
    </tr>
    <?php
    $totalReceipt = 0.00;
    $grandTotalReceive = 0.00;
    $grandTotalPayment = 0.00;
    $grandTotalDebit = 0.00;
    ?>
    <?php foreach ($purchaseReceiptSummary->dataProvider->data as $header): ?>
        <tr class="items1">
            <td class="width1-1" style="text-align: center"><?php echo CHtml::encode($header->getCodeNumber(PurchaseReceiptHeader::CN_CONSTANT)); ?></td>
            <td class="width1-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($header->date))); ?></td>
            <td class="width1-3" style="text-align: center"><?php echo CHtml::encode(CHtml::value($header, 'supplier.company')); ?></td>
            <td class="width1-4" style="text-align: left"><?php echo nl2br(CHtml::encode(CHtml::value($header, 'note'))); ?></td>
        </tr>
        <tr class="items2">
            <?php if ($header->purchasePaymentHeaders != null) : ?>
                <td colspan="4" style="border-bottom: 0px solid;">
                <?php else: ?>
                <td colspan="4">  
                <?php endif; ?>
                <table>					
                    <?php foreach ($header->purchaseReceiptDetails as $detail): ?>
                        <tr>
                            <td class="width2-1" style="text-align: center"><?php echo CHtml::encode($detail->receiveHeader->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?></td>
                            <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->receiveHeader->purchaseHeader->date))); ?></td>
                            <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'receiveHeader.grandTotalReceipt'))); ?></td>
                            <td class="width2-4" style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" style="border-top: 0px solid;text-align: right; font-weight:bold">Total</td>
                        <td class="width2-3" style="border-top: 1px solid;text-align: right; font-weight:bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->totalReceive)); ?></td>
                        <?php if ($header->purchasePaymentHeaders == null): ?>
                            <td class="width2-4" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $header->totalReceive)); ?></td>
                        <?php else: ?>
                            <td class="width2-4"></td>
                        <?php endif; ?>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if ($header->purchasePaymentHeaders != null) : ?>
            <tr class="items2">
                <td colspan="4">
                    <table>	
                        <?php $totalPayment = 0.00; ?>
                        <?php foreach ($header->purchasePaymentHeaders as $detail): ?>
                            <tr>
                                <td class="width2-1" style="text-align: center"><?php echo CHtml::encode($detail->getCodeNumber(PurchasePaymentHeader::CN_CONSTANT)); ?></td>
                                <td class="width2-2" style="text-align: center"><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($detail->date))); ?></td>
                                <td class="width2-3" style="text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', CHtml::value($detail, 'amountPaid'))); ?></td>
                                <td class="width2-4" style="text-align: center"><?php echo CHtml::encode(CHtml::value($detail, 'memo')); ?></td>
                            </tr>
                            <?php $totalPayment += $detail->amountPaid; ?>
                        <?php endforeach; ?>
                        <?php $totalDebit = $header->totalReceive - $totalPayment; ?>
                        <tr>
                            <td colspan="2" style="border-top: 0px solid;text-align: right; font-weight:bold">Total</td>
                            <td class="width2-3" style="border-top: 1px solid;text-align: right; font-weight:bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $totalPayment)); ?></td>
                            <td class="width2-4" style="text-align: right;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $totalDebit)); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php $grandTotalPayment += $totalPayment; ?>
            <?php $grandTotalDebit += $totalDebit; ?>
        <?php else: ?>
            <?php $grandTotalDebit += $header->totalReceive; ?> 
        <?php endif; ?>        

        <?php $totalReceipt += CHtml::value($header, 'totalReceivePrice'); ?>
        <?php $grandTotalReceive += $header->totalReceive; ?>
    <?php endforeach; ?>
    <tr id="header2">
        <td colspan="4" style="border-bottom: 0px solid">
            <table>      
                <tr>
                    <th class="width2-1" style=" border-bottom: 0px solid;"></th>
                    <th class="width2-2" style="text-align: right; border-bottom: 0px solid;">Grand Total Pembelian</th>
                    <th class="width2-3" style="text-align: right; border-bottom: 0px solid;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalReceive)); ?></th>
                    <th class="width2-4" style=" border-bottom: 0px solid;"></th>

                </tr>
                <tr>
                    <th class="width2-1" style=" border-bottom: 0px solid;"></th>
                    <th class="width2-2" style="text-align: right; border-bottom: 0px solid;">Grand Total Pembayaran</th>
                    <th class="width2-3" style="text-align: right; border-bottom: 0px solid;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalPayment)); ?></th>
                    <th class="width2-4" style=" border-bottom: 0px solid; text-align: right;"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $grandTotalDebit)); ?></th>

                </tr>
            </table>
        </td>
    </tr>     
<!--	<tr>
<td colspan="3" style="border-top: 1px solid; font-weight: bold; text-align: right">TOTAL</td>
<td class="width2-4" style="border-top: 1px solid; font-weight: bold; text-align: right"><?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $totalReceipt));                ?></td>
</tr>-->
</table>
