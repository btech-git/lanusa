<table style="border: 1px solid">
    <tr style="background-color: skyblue">
		<th style="text-align: center">Penjualan #</th>
        <th style="text-align: center">Tanggal</th>
        <th style="text-align: center">Catatan</th>
        <th style="text-align: center">Total</th>
		<th></th>
    </tr>
    <?php foreach ($purchaseInvoice->details as $i => $detail): ?>
        <tr style="background-color: azure">
            <td style="text-align: center; width: 15%">
				<?php echo CHtml::activeHiddenField($detail, "[$i]purchase_header_id"); ?>
                <?php echo CHtml::encode($detail->purchaseHeader->getCodeNumber(PurchaseHeader::CN_CONSTANT)); ?>
            </td>
            <td style="text-align: center; width: 15%">
                <?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail->purchaseHeader, 'date')))); ?>
            </td>
            <td>
                <?php echo CHtml::encode(CHtml::value($detail->purchaseHeader, 'note')); ?>
            </td>
            <td style="text-align: right; width: 15%">
                <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail->purchaseHeader, 'grandTotal'))); ?>
            </td>
			<td style="width: 5%">
				<?php if ($detail->isNewRecord): ?>
					<?php echo CHtml::button('Delete', array(
						'onclick'=>CHtml::ajax(array(
							'type'=>'POST',
							'url'=>CController::createUrl('ajaxHtmlRemoveDetail', array('id' => $purchaseInvoice->header->id, 'index'=>$i)),
							'update'=>'#detail_div',
						)),
					)); ?>
				<?php else: ?>
					<?php echo CHtml::activeDropDownList($detail, "[$i]is_inactive", array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
				<?php endif; ?>
			</td>
        </tr>
    <?php endforeach; ?>
		
    <tr style="background-color: aquamarine">
        <td colspan="3" style="text-align: right; font-weight: bold">Total Invoice:</td>
        <td style="text-align: right; font-weight: bold">
            <?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseInvoice->totalPurchase)); ?>
        </td>
		<td></td>
    </tr>
</table>