<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center; width: 15%">Penerimaan #</th>
		<th style="text-align: center; width: 15%">Tanggal</th>
		<th style="text-align: center; width: 15%">Total</th>
		<th style="text-align: center">Memo</th>
		<th></th>
	</tr>
	<?php foreach ($purchaseReceipt->details as $i=>$detail): ?>
	
		<tr style="background-color: azure">
			<td>
				<?php echo CHtml::activeHiddenField($detail, "[$i]receive_header_id"); ?>
				<?php echo CHtml::encode($detail->receiveHeader->getCodeNumber(ReceiveHeader::CN_CONSTANT)); ?>
			</td>
			<td>
				<?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail->receiveHeader, 'date')))); ?>
			</td>
			<td style="text-align: right">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail->receiveHeader, 'grandTotalReceipt'))); ?>
			</td>
			<td style="text-align: center">
				<?php echo CHtml::activeTextField($detail, "[$i]memo", array('size'=>50, 'maxlength'=>60)); ?>
				<?php echo CHtml::error($detail, 'memo'); ?>
			</td>
			<td style="width: 5%">
				<?php if ($detail->isNewRecord): ?>
					<?php echo CHtml::button('Delete', array(
						'onclick'=>CHtml::ajax(array(
							'type'=>'POST',
							'url'=>CController::createUrl('ajaxHtmlRemoveDetail', array('id' => $purchaseReceipt->header->id,'index'=>$i)),
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
		<td colspan="2" style="font-weight: bold; text-align: right">TOTAL</td>
		<td style="font-weight: bold; text-align: right"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $purchaseReceipt->totalReceivePrice)); ?></td>
		<td colspan="2"></td>
	</tr>
</table>
