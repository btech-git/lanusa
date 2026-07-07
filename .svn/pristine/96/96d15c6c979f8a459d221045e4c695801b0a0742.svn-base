<table style="border: 1px solid">
        <tr style="background-color: skyblue">
                <th style="text-align: center">Kode Akun</th>
                <th style="text-align: center">Nama Akun</th>
                <th style="text-align: center">Jumlah</th>
                <th style="text-align: center">Memo</th>
                <th style="text-align: center"></th>
        </tr>
        <?php foreach ($deposit->details as $i=>$detail): ?>
        <tr style="background-color: azure">
			<td style="width: 10%">
				<?php echo CHtml::activeHiddenField($detail, "[$i]account_id"); ?>
				<?php echo CHtml::encode(CHtml::value($detail, 'account.code')); ?>
				<?php echo CHtml::error($detail, 'account_id'); ?>
			</td>
			<td style="width: auto">
				<?php echo CHtml::encode(CHtml::value($detail, 'account.name')); ?>
			</td>
			<td style="text-align: center; width: 15%">
				<?php echo CHtml::activeTextField($detail, "[$i]amount", array('size'=>10, 'maxlength'=>18,
					'onchange'=>CHtml::ajax(array(
						'type'=>'POST',
						'dataType'=>'JSON',
						'url'=>CController::createUrl('AjaxJsonTotal', array('id'=>$deposit->header->id,'index'=>$i)),
						'success'=>'function(data) {
							$("#amount_'.$i.'").html(data.amount);
							$("#total").html(data.total);
						}',
					)),
				)); ?>
				<div id="amount_<?php echo $i; ?>" style="text-align: left; font-size: smaller">
					<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'amount'))); ?>
				</div>
				<?php echo CHtml::error($detail, 'amount'); ?>
			</td>
			<td style="text-align: center; width: 15%">
				<?php echo CHtml::activeTextField($detail, "[$i]memo", array('size'=>30, 'maxlength'=>200)); ?>
				<?php echo CHtml::error($detail, 'memo'); ?>
			</td>
			<td style="width: 5%">
				<?php if ($detail->isNewRecord): ?>
					<?php echo CHtml::button('Delete', array(
						'onclick'=>CHtml::ajax(array(
							'type'=>'POST',
							'url'=>CController::createUrl('AjaxHtmlRemoveAccount', array('id'=>$deposit->header->id,'index'=>$i)),
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
			<td></td>
			<td style="font-weight: bold; text-align: right">Total</td>
			<td style="font-weight: bold; text-align: center">
				<span id="total">
						<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $deposit->total)); ?>
				</span>
			</td>
			<td></td>
			<td></td>
        </tr>
</table>