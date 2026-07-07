<table style="border: 1px solid">
    <tr style="background-color: skyblue">
        <th style="text-align: center">Nama Barang</th>
        <th style="text-align: center">Ukuran</th>
        <?php if ($delivery->header->isNewRecord): ?>
            <th style="text-align: center">Jmlh Jual</th>
        <?php endif; ?>
        <th style="text-align: center">Jumlah</th>
        <th style="text-align: center">Satuan</th>
        <th style="text-align: center">Gudang</th>
        <th></th>
    </tr>
    <?php foreach ($delivery->details as $i => $detail): ?>

        <?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>

        <tr style="background-color: azure">
            <td>
                <?php echo CHtml::activeHiddenField($detail, "[$i]product_id"); ?>
                <?php echo CHtml::activeHiddenField($detail, "[$i]sale_detail_id"); ?>
                <?php echo CHtml::encode($detail->getProductName($delivery->header->sale_header_id)); ?>
            </td>
            <td style="text-align: center; width: 5%">
                <?php echo CHtml::encode(CHtml::value($detailProduct, 'size')); ?>
            </td>
            <?php if ($delivery->header->isNewRecord): ?>
                <td style="text-align: center; width: 10%">
                    <?php echo CHtml::hiddenField("quantity_ordered_{$i}", ($quantityOrdered = $detail->getQuantityOrdered($delivery->header->sale_header_id))); ?>
                    <span>
                        <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $quantityOrdered)); ?>
                    </span>
                </td>
            <?php endif; ?>
            <td style="text-align: center; width: 10%">
                <?php echo CHtml::activeTextField($detail, "[$i]quantity", array('size' => 7, 'maxLength' => 20,
                    'onchange' => '
                        if (parseInt($(this).val()) > parseInt($("#quantity_ordered_' . $i . '").val())) 
                            $(this).val($("#quantity_ordered_' . $i . '").val())'

//                    'onchange' => ($delivery->header->is_non_tax) ? '' : 'if (parseInt($(this).val()) > parseInt($("#quantity_ordered_' . $i . '").val())) $(this).val($("#quantity_ordered_' . $i . '").val());' .
//                        CHtml::ajax(array(
//                            'type' => 'POST',
//                            'dataType' => 'JSON',
//                            'url' => CController::createUrl('AjaxJsonTotal', array('id' => $delivery->header->id, 'index' => $i)),
//                            'success' => 'function(data) {
//							$("#unit_price_' . $i . '").html(data.unitPrice);
//							$("#total_' . $i . '").html(data.total);
//							$("#sub_total").html(data.subTotal);
//							$("#discount").html(data.discount);
//							$("#tax").html(data.tax);
//							$("#grand_total").html(data.grandTotal);
//						}',
//					)),
                )); ?>
                <?php echo CHtml::error($detail, 'quantity'); ?>
            </td>
            <td style="text-align: center; width: 10%">
                <?php echo CHtml::encode($detail->getProductUnit($delivery->header->sale_header_id)); ?>
            </td>

            <td style="text-align: center">
                <?php echo CHtml::activeDropDownList($detail, "[$i]warehouse_id", CHtml::listData(Warehouse::model()->findAll(), 'id', 'name'));
                ?>
            </td>

            <td style="width: 5%">
                <?php if ($detail->isNewRecord): ?>
                    <?php echo CHtml::button('Delete', array(
                        'onclick' => CHtml::ajax(array(
                            'type' => 'POST',
                            'url' => CController::createUrl('AjaxHtmlRemoveProduct', array('id' => $delivery->header->id, 'index' => $i)),
                            'update' => '#detail_div',
                        )),
                    )); ?>
                <?php else: ?>
                    <?php echo CHtml::activeDropDownList($detail, "[$i]is_inactive", array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
