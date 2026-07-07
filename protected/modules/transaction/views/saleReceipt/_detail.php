<table style="border: 1px solid">
    <tr style="background-color: skyblue">
        <th style="text-align: center; width: 20%">Invoice #</th>
        <th style="text-align: center; width: 25%">Tanggal</th>
        <th style="text-align: center; width: 25%">Total(Rp)</th>
        <th style="text-align: center; width: 30%">Memo</th>
        <th></th>
    </tr>
    <?php $grandTotal = 0.00; ?>
    <?php foreach ($saleReceipt->details as $i => $detail): ?>
        <?php $totalInvoice = CHtml::value($detail, 'saleInvoice.grandTotal'); ?>
        <tr style="background-color: azure">
            <td>
                <?php echo CHtml::activeHiddenField($detail, "[$i]sale_invoice_id"); ?>
                <?php echo ($detail->saleInvoice != null) ? CHtml::encode($detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)) : 'No Sale Invoice'; 
                ?>
            </td>
            
            <td style="text-align: center">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleInvoice.date')))); ?>
            </td>

            <td style="text-align: right">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $totalInvoice)); ?>
            </td>
            
            <td>
                <?php echo CHtml::activeTextField($detail, "[$i]memo", array('size' => 30, 'maxLength' => 100)); ?>
            </td>
            
            <td style="width: 5%">
                <?php if ($detail->isNewRecord): ?>
                    <?php echo CHtml::button('Delete', array(
                        'onclick' => CHtml::ajax(array(
                            'type' => 'POST',
                            'url' => CController::createUrl('ajaxHtmlRemoveDetail', array('id' => $saleReceipt->header->id, 'index' => $i)),
                            'update' => '#detail_div',
                        )),
                    )); ?>
                <?php else: ?>
                    <?php echo CHtml::activeDropDownList($detail, "[$i]is_inactive", array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php $grandTotal += $totalInvoice; ?>
    <?php endforeach; ?>
    <tr style="background-color: aquamarine">
        <td style="text-align: right; font-weight: bold" colspan="2">Total</td>
        <td style="text-align: right; font-weight: bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $grandTotal)); ?></td>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
