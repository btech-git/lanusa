<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center">Nama Barang</th>
		<th style="text-align: center">Ukuran</th>
		<th style="text-align: center">Stok</th>
		<th style="text-align: center">Jumlah</th>
		<th style="text-align: center">Satuan</th>
		<th style="text-align: center"></th>
	</tr>
	<?php foreach ($transfer->details as $i=>$detail): ?>
		
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>
	
        <tr style="background-color: azure">
			<td style="width: auto">
				<?php echo CHtml::activeHiddenField($detail, "[$i]product_id"); ?>
				<?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::hiddenField("current_stock_{$i}", ($currentStock = $detail->getCurrentStock($transfer->header->warehouse_id_from))); ?>
				<span><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $currentStock)); ?></span>
			</td>
			<td style="text-align: center; width: 15%">
				<?php echo CHtml::activeTextField($detail, "[$i]quantity", array('size'=>7, 'maxLength'=>20,
					'onchange'=>'if (parseInt($(this).val()) > parseInt($("#current_stock_'.$i.'").val())) $(this).val($("#current_stock_'.$i.'").val())'
				)); ?>
				<?php echo CHtml::error($detail, 'quantity'); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?>
				<?php echo CHtml::error($detail, 'product.unit.name'); ?>
			</td>
			<td style="width: 5%">
				<?php echo CHtml::button('Delete', array(
					'onclick'=>CHtml::ajax(array(
						'type'=>'POST',
						'url'=>CController::createUrl('ajaxHtmlRemoveProduct', array('id' => $transfer->header->id, 'index' => $i)),
						'update'=>'#detail_div',
					)),
				)); ?>
			</td>
        </tr>
	<?php endforeach; ?>
</table>