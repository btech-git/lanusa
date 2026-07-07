<table style="border: 1px solid">
    <tr style="background-color: skyblue">
        <th style="text-align: center">Nama Barang</th>
        <th style="text-align: center">Ukuran</th>
		<?php if ($purchaseReturn->header->isNewRecord): ?>
			<th style="text-align: center">Jml Terima</th>
		<?php endif; ?>
        <th style="text-align: center">Jml Retur</th>
        <th style="text-align: center">Satuan</th>
        <th style="text-align: center">Harga Satuan</th>
        <th style="text-align: center">Total</th>
        <th style="text-align: center"></th>
    </tr>
    <?php foreach ($purchaseReturn->details as $i => $detail): ?>
		
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>
	
        <tr style="background-color: azure">
            <td>
                <?php echo CHtml::activeHiddenField($detail, "[$i]product_id"); ?>
                <?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?>
            </td>
            <td style="text-align: center; width: 15%">
                <?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?>
            </td>
			<?php if ($purchaseReturn->header->isNewRecord): ?>
				<td style="text-align: center; width: 5%">
					<?php echo CHtml::hiddenField("quantity_received_{$i}", ($quantityReceived = $detail->getQuantityReceived($purchaseReturn->header->receive_header_id))); ?>
					<span><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $quantityReceived)); ?></span>
				</td>
			<?php endif; ?>
            <td style="text-align:center; width: 10%">
                <?php
                echo CHtml::activeTextField($detail, "[$i]quantity", array('size' => 5, 'maxLength' => 20,
                    'onchange' => 'if (parseInt($(this).val()) > parseInt($("#quantity_received_' . $i . '").val())) $(this).val($("#quantity_received_' . $i . '").val());' .
                    CHtml::ajax(array(
                        'type' => 'POST',
                        'dataType' => 'JSON',
                        'url' => CController::createUrl('ajaxJsonTotal', array('id' => $purchaseReturn->header->id, 'index' => $i)),
                        'success' => 'function(data) {
                            $("#total_' . $i . '").html(data.total);
                            $("#sub_total").html(data.subTotal);
                            $("#tax").html(data.tax);
                            $("#grand_total").html(data.grandTotal);
                        }',
                    )),
                ));
                ?>
                <?php echo CHtml::error($detail, 'quantity'); ?>
            </td>
            <td style="text-align: center; width: 5%">
                <?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?>
                <?php echo CHtml::error($detail, 'product.unit.name'); ?>
            </td>
            <td style="text-align: right; width: 10%">
                <?php echo CHtml::hiddenField("unit_price_{$i}", ($unitPrice = $detail->getUnitPrice($purchaseReturn->header->receive_header_id))); ?>
                <span><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $unitPrice)); ?></span>
            </td>
            <td style="text-align: right; width: 15%">
                <span id="total_<?php echo $i; ?>">
					<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->getTotal($purchaseReturn->header->receive_header_id))); ?>
                </span>
            </td>
            <td style="width: 5%">
                <?php if ($detail->isNewRecord): ?>
                    <?php
                    echo CHtml::button('Delete', array(
                        'onclick' => CHtml::ajax(array(
                            'type' => 'POST',
                            'url' => CController::createUrl('ajaxHtmlRemoveProduct', array('id' => $purchaseReturn->header->id, 'index' => $i)),
                            'update' => '#detail_div',
                        )),
                    ));
                    ?>
                <?php else: ?>
                    <?php echo CHtml::activeDropDownList($detail, "[$i]is_inactive", array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr style="background-color: aquamarine">
        <td colspan="2"></td>
		<?php if ($purchaseReturn->header->isNewRecord): ?>
			<td></td>
		<?php endif; ?>
        <td colspan="3" style="text-align: right">Sub Total:</td>
        <td style="text-align: right">
            <span id="sub_total">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getSubTotal($purchaseReturn->header->receive_header_id))); ?>
            </span>
        </td>
        <td></td>
    </tr>
    <tr style="background-color: aquamarine">
        <td colspan="2"></td>
		<?php if ($purchaseReturn->header->isNewRecord): ?>
			<td></td>
		<?php endif; ?>
        <td colspan="3" style="text-align: right">
            PPN
            <?php
            echo CHtml::activeTextField($purchaseReturn->header, 'tax', array('size' => 2, 'maxlength' => 2,
                'onchange' => CHtml::ajax(array(
                    'type' => 'POST',
                    'dataType' => 'JSON',
                    'url' => CController::createUrl('ajaxJsonGrandTotal', array('id' => $purchaseReturn->header->id)),
                    'success' => 'function(data) {
                        $("#tax").html(data.tax);
                        $("#grand_total").html(data.grandTotal);
                    }',
                )),
            ));
            ?>
            <?php echo '%'; ?>
        </td>
        <td style="text-align: right">
            <span id="tax">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getCalculatedTax($purchaseReturn->header->receive_header_id))); ?>
            </span>
        </td>
        <td></td>
    </tr>
    <tr style="background-color: aquamarine">
        <td colspan="2"></td>
		<?php if ($purchaseReturn->header->isNewRecord): ?>
			<td></td>
		<?php endif; ?>
        <td colspan="3" style="text-align: right">Ongkos Kirim:</td>
        <td style="text-align: right">
            <?php
            echo CHtml::activeTextField($purchaseReturn->header, 'shipping_fee', array('size' => 10, 'maxLength' => 20,
                'onchange' => CHtml::ajax(array(
                    'type' => 'POST',
                    'dataType' => 'JSON',
                    'url' => CController::createUrl('ajaxJsonGrandTotal', array('id' => $purchaseReturn->header->id)),
                    'success' => 'function(data) {
                        $("#grand_total").html(data.grandTotal);
                    }',
                )),
            ));
            ?>
            <?php echo CHtml::error($purchaseReturn->header, 'shipping_fee'); ?>
        </td>
        <td></td>
    </tr>
    <tr style="background-color: aquamarine">
        <td colspan="2"></td>
		<?php if ($purchaseReturn->header->isNewRecord): ?>
			<td></td>
		<?php endif; ?>
		<td colspan="3" style="font-weight: bold; text-align: right">Grand Total:</td>
        <td style="font-weight: bold; text-align: right">
            <span id="grand_total">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReturn->getGrandTotal($purchaseReturn->header->receive_header_id))); ?>
            </span>
        </td>
        <td></td>
    </tr>
</table>
