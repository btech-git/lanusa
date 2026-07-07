<table style="border: 1px solid">
    <tr style="background-color: skyblue">
        <th style="text-align: center">Nama Akun</th>
        <th style="text-align: center">Jenis Pembayaran</th>
        <th style="text-align: center">Jumlah</th>
        <th style="text-align: center">Memo</th>
        <th style="text-align: center"></th>
    </tr>
    <?php foreach ($purchasePayment->details as $i => $detail): ?>
        <tr style="background-color: azure">
            <td style="width: auto">
                <?php echo CHtml::activeHiddenField($detail, "[$i]account_id"); ?>
                <?php echo CHtml::encode(CHtml::value($detail, 'account.name')); ?>
            </td>
            <td style="text-align: center">
                <?php echo CHtml::activeDropDownList($detail, "[$i]payment_type_id", CHtml::listData(PaymentType::model()->findAll(), 'id', 'name')); ?>
                <?php echo CHtml::error($detail, 'payment_type_id'); ?>
            </td>
            <td style="text-align: center">
                <?php echo CHtml::activeTextField($detail, "[$i]amount", array(
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => 'JSON',
                        'url' => CController::createUrl('AjaxJsonSummary', array('id' => $purchasePayment->header->id, 'index' => $i)),
                        'success' => 'function(data) {
                            $("#amount_' . $i . '").html(data.amount);
                            $("#payment").html(data.payment);
                            $("#remaining").html(data.remaining);
                            $("#total_payment").html(data.total_payment);
                        }',
                    )),
                )); ?>
                <div id="amount_<?php echo $i; ?>" style="text-align: left; font-size: smaller">
                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'amount'))); ?>
                </div>
                <?php echo CHtml::error($detail, 'amount'); ?>
            </td>
            <td style="text-align: center">
                <?php echo CHtml::activeTextField($detail, "[$i]memo", array('size' => 50, 'maxlength' => 200)); ?>
                <?php echo CHtml::error($detail, 'memo'); ?>
            </td>
            <td style="width: 5%">
                <?php if ($detail->isNewRecord): ?>
                    <?php echo CHtml::button('Delete', array(
                        'onclick' => CHtml::ajax(array(
                            'type' => 'POST',
                            'url' => CController::createUrl('AjaxHtmlRemovePayment', array('id' => $purchasePayment->header->id, 'index' => $i)),
                            'update' => '#detail_div',
                        )),
                    )); ?>
                <?php else: ?>
                    <?php echo CHtml::activeDropDownList($detail, "[$i]is_inactive", array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
                <?php endif; ?>
            </td>
        </tr>
                <?php endforeach; ?>
    <tr style="background-color: aquamarine">
        <td colspan="2" style="font-weight: bold">Total Pembayaran:</td>
        <td style="text-align: right;font-weight: bold">
            <span id="total_payment">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchasePayment, 'totalPayment'))); ?>
            </span>
        </td>
        <td></td>
        <td></td>
    </tr>

    <tr style="background-color: aquamarine">
        <td colspan="2" style="font-weight: bold">Total Pembelian:</td>
        <td style="font-weight: bold; text-align: right">
            <span id="total">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchasePayment, 'totalPurchase'))); ?>
            </span>
        </td>
        <td></td>
        <td></td>
    </tr>

<?php //if ($purchasePayment->header->isNewRecord):  ?>
<!--	<tr style="background-color: aquamarine">
        <td></td>
        <td></td>
        <td style="font-weight: bold">Pembayaran Lunas:</td>
        <td style="font-weight: bold; text-align: right">
                <span id="payment">
<?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchasePayment, 'payment')));  ?>
                </span>
        </td>
        <td></td>
</tr>
<tr style="background-color: aquamarine">
        <td></td>
        <td></td>
        <td style="font-weight: bold">Sisa Pembayaran:</td>
        <td style="font-weight: bold; text-align: right">
                <span id="remaining">
    <?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($purchasePayment, 'remaining')));  ?>
                </span>
        </td>
        <td></td>
</tr>-->
<?php //endif;  ?>
</table>
