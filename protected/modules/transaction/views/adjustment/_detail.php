<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center">Nama Produk</th>
		<th style="text-align: center">Ukuran</th>
		<th style="text-align: center">Sekarang</th>
		<th style="text-align: center">Penyesuaian</th>
		<th style="text-align: center">Perbedaan</th>
		<th style="text-align: center">Satuan</th>
		<th style="text-align: center"></th>
	</tr>
	<?php foreach ($adjustment->details as $i=>$detail): ?>
		
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>
	
        <tr style="background-color: azure">
			<td>
				<?php echo CHtml::activeHiddenField($detail, "[$i]product_id"); ?>
				<?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::encode(CHtml::value($detailProduct, 'size')); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::activeHiddenField($detail, "[$i]quantity_current", array('value' => ($quantityCurrent = $detail->getCurrentStock($adjustment->header->warehouse_id)))); ?>
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $quantityCurrent)); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::activeTextField($detail, "[$i]quantity_adjustment", array('size'=>7, 'maxLength'=>20,
					'onchange'=>CHtml::ajax(array(
						'type'=>'POST',
						'dataType'=>'JSON',
						'url'=>CController::createUrl('ajaxJsonDifference', array('id'=>$adjustment->header->id, 'index'=>$i)),
						'success'=>'function(data) {
							$("#quantity_difference_' . $i . '").html(data.quantityDifference);
						}',
					)),
				)); ?>
				<?php echo CHtml::error($detail, 'quantity_adjustment'); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<span id="quantity_difference_<?php echo $i; ?>">
					<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $detail->getQuantityDifference($adjustment->header->warehouse_id))); ?>
				</span>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::encode(CHtml::value($detailProduct, 'unit.name')); ?>
			</td>
			<td style="width: 5%">
				<?php echo CHtml::button('Delete', array(
					'onclick'=>CHtml::ajax(array(
						'type'=>'POST',
						'url'=>CController::createUrl('ajaxHtmlRemoveProduct', array('id' => $adjustment->header->id, 'index' => $i)),
						'update'=>'#detail_div',
					)),
				)); ?>
			</td>
        </tr>
	<?php endforeach; ?>
</table>
