<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center">Nama Barang</th>
		<th style="text-align: center">Ukuran</th>
		<?php if ($receive->header->isNewRecord): ?>
			<th style="text-align: center">Jumlah Pesan</th>
		<?php endif; ?>
		<th style="text-align: center">Jumlah Terima</th>
		<th style="text-align: center">Satuan</th>
		<th>Gudang</th>
		<th style="text-align: center"></th>
	</tr>
	<?php foreach ($receive->details as $i=>$detail): ?>
		
		<?php $detailProduct = $detail->product(array('scopes' => 'resetScope', 'with' => 'unit:resetScope')); ?>
	
        <tr style="background-color: azure">
			<td style="width: auto">
				<?php echo CHtml::activeHiddenField($detail, "[$i]product_id"); ?>
				<?php echo CHtml::activeHiddenField($detail, "[$i]purchase_detail_id"); ?>
				<?php echo CHtml::encode(CHtml::value($detailProduct, 'name')); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::encode(CHtml::value($detail, 'product.size')); ?>
			</td>
			<?php if ($receive->header->isNewRecord): ?>
				<td style="text-align:right; width: 10%">
					<?php echo CHtml::hiddenField("quantity_ordered_{$i}", ($quantityOrdered = $detail->getQuantityOrdered($receive->header->purchase_header_id))); ?>
					<span><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $quantityOrdered)); ?></span>
				</td>
			<?php endif; ?>
			<td style="text-align:center; width: 15%">
				<?php echo CHtml::activeTextField($detail, "[$i]quantity", array('size'=>5, 'maxlength'=>10, 
					'onchange'=>'if (parseInt($(this).val()) > parseInt($("#quantity_ordered_'.$i.'").val())) $(this).val($("#quantity_ordered_'.$i.'").val())'
				)); ?>
				<?php echo CHtml::error($detail, 'quantity'); ?>
			</td>
			<td style="text-align: center; width: 10%">
				<?php echo CHtml::encode(CHtml::value($detail, 'product.unit.name')); ?>
			</td>
			
			<td>
				<?php echo CHtml::activeDropDownList($detail, "[$i]warehouse_id", CHtml::listData(Warehouse::model()->findAll(), 'id', 'name'), array('empty' => '-- Pilih Warehouse --')); ?>
				<?php echo CHtml::error($detail,'warehouse_id'); ?>
			</td>
			
			<td style="width: 5%">
				<?php if ($detail->isNewRecord): ?>
					<?php echo CHtml::button('Delete', array(
						'onclick'=>CHtml::ajax(array(
							'type'=>'POST',
							'url'=>CController::createUrl('ajaxHtmlRemoveProduct', array('id'=>$receive->header->id, 'index'=>$i)),
							'update'=>'#detail_div',
						)),
					)); ?>
				<?php else: ?>
					<?php echo CHtml::activeDropDownList($detail, "[$i]is_inactive", array(ActiveRecord::ACTIVE => 'Active', ActiveRecord::INACTIVE => 'Inactive')); ?>
				<?php endif; ?>
			</td>
        </tr>
	<?php endforeach; ?>
</table>
