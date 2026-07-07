<table style="border: 1px solid">
    <tr style="background-color: skyblue">
        <th style="text-align: center">Nama Barang</th>
        <th style="text-align: center">Ukuran</th>
        <th style="text-align: center">Jumlah</th>
        <th style="text-align: center">Satuan</th>
        <th style="text-align: center">Harga Satuan</th>
        <th style="text-align: center">Total</th>
        <th style="text-align: center"></th>
    </tr>
    <?php foreach ($purchase->details as $i => $detail): ?>

        <?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>

        <tr style="background-color: azure">
            <td style="width: auto">
                <?php echo CHtml::activeHiddenField($detail, "[$i]product_id"); ?>
                <?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?>
            </td>
            <td style="text-align: center; width: 5%">
                <?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?>
            </td>
            <td style="text-align: center; width: 10%">
                <?php echo CHtml::activeTextField($detail, "[$i]quantity", array(
                    'size' => 7, 
                    'maxLength' => 20,
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => 'JSON',
                        'url' => CController::createUrl('ajaxJsonTotal', array('id' => $purchase->header->id, 'index' => $i)),
                        'success' => 'function(data) {
                            $("#total_' . $i . '").html(data.total);
                            $("#sub_total").html(data.subTotal);
                            $("#taxPercentage").html(data.taxPercentage);
                            $("#taxValue").html(data.taxValue);
                            $("#grand_total").html(data.grandTotal);
                        }',
                    )),
                )); ?>
                <?php echo CHtml::error($detail, 'quantity'); ?>
            </td>
            <td style="text-align: center; width: 5%">
                <?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?>
            </td>
            <td style="text-align: center; width: 15%">
                <?php echo CHtml::activeTextField($detail, "[$i]unit_price", array(
                    'size' => 10, 
                    'maxLength' => 20,
                    'onchange' => CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => 'JSON',
                        'url' => CController::createUrl('ajaxJsonTotal', array('id' => $purchase->header->id, 'index' => $i)),
                        'success' => 'function(data) {
                            $("#unit_price_' . $i . '").html(data.unitPrice);
                            $("#total_' . $i . '").html(data.total);
                            $("#sub_total").html(data.subTotal);
                            $("#taxPercentage").html(data.taxPercentage);
                            $("#taxValue").html(data.taxValue);
                            $("#grand_total").html(data.grandTotal);
                        }',
                    )),
                )); ?>
                <div id="unit_price_<?php echo $i; ?>" style="text-align: left; font-size: smaller">
                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'unit_price'))); ?>
                </div>
                <?php echo CHtml::error($detail, 'unit_price'); ?>
            </td>
            <td style="text-align: right; width: 15%">
                <span id="total_<?php echo $i; ?>">
                    <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'total'))); ?>
                </span>
            </td>
            <td style="width: 5%">
                <?php if ($detail->isNewRecord): ?>
                    <?php echo CHtml::button('Delete', array(
                        'onclick' => CHtml::ajax(array(
                            'type' => 'POST',
                            'url' => CController::createUrl('ajaxHtmlRemoveProduct', array('id' => $purchase->header->id, 'index' => $i)),
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
        <td colspan="5" style="text-align: right">Sub Total:</td>
        <td style="text-align: right">
            <span id="sub_total">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->subTotal)); ?>
            </span>
        </td>
        <td></td>
    </tr>
    <tr style="background-color: aquamarine">
        <td colspan="5" style="text-align: right">
            PPN <?php echo CHtml::activeTextField($purchase->header, 'tax'); ?>%
        </td>
        <td style="text-align: right">
            <span id="taxValue">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->calculatedTax)); ?>
            </span>
        </td>
        <td></td>
    </tr>
    <tr style="background-color: aquamarine">
        <td colspan="5" style="font-weight: bold; text-align: right">Grand Total:</td>
        <td style="text-align: right; font-weight: bold">
            <span id="grand_total">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchase->grandTotal)); ?>
            </span>
        </td>
        <td></td>
    </tr>
</table>