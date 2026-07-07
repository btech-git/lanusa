<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center">Kode Akun</th>
		<th style="text-align: center">Nama Akun</th>
		<th style="text-align: center">Debit</th>
		<th style="text-align: center">Kredit</th>
		<th style="text-align: center">Memo</th>
		<th style="text-align: center"></th>
	</tr>
	<?php foreach ($journal->details as $i=>$detail): ?>
	<tr style="background-color: azure">
		<td style="width: 10%">
			<?php echo CHtml::activeHiddenField($detail, "[$i]account_id"); ?>
			<?php echo CHtml::encode(CHtml::value($detail, 'account.code')); ?>
			<?php echo CHtml::error($detail, 'account_id'); ?>
		</td>
		<td style="width: auto">
			<?php echo CHtml::encode(CHtml::value($detail, 'account.name')); ?>
		</td>
		<td style="text-align: center; width: 10%">
			<?php echo CHtml::activeTextField($detail, "[$i]debit", array('size'=>10, 'maxlength'=>18,
				'onchange'=>CHtml::ajax(array(
					'type'=>'POST',
					'dataType'=>'JSON',
					'url'=>CController::createUrl('AjaxJsonTotalDebit', array('id'=>$journal->header->id,'index'=>$i)),
					'success'=>'function(data) {
						$("#debit_'.$i.'").html(data.debit);
						$("#total_debit").html(data.totalDebit);
					}',
				)),
			)); ?>
			<div id="debit_<?php echo $i; ?>" style="text-align: left; font-size: smaller">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'debit'))); ?>
			</div>
			<?php echo CHtml::error($detail, 'debit'); ?>
		</td>
		<td style="text-align: center; width: 10%">
			<?php echo CHtml::activeTextField($detail, "[$i]credit", array('size'=>10, 'maxlength'=>18,
					'onchange'=>CHtml::ajax(array(
							'type'=>'POST',
							'dataType'=>'JSON',
							'url'=>CController::createUrl('AjaxJsonTotalCredit', array('id'=>$journal->header->id,'index'=>$i)),
							'success'=>'function(data) {
									$("#credit_'.$i.'").html(data.credit);
									$("#total_credit").html(data.totalCredit);
							}',
					)),
			)); ?>
			<div id="credit_<?php echo $i; ?>" style="text-align: left; font-size: smaller">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'credit'))); ?>
			</div>
			<?php echo CHtml::error($detail, 'credit'); ?>
		</td>
		<td style="text-align: center; width: 25%">
			<?php echo CHtml::activeTextField($detail, "[$i]memo", array('size'=>30, 'maxlength'=>200)); ?>
			<?php echo CHtml::error($detail, 'memo'); ?>
		</td>
		<td style="width: 5%">
			<?php if ($detail->isNewRecord): ?>
				<?php echo CHtml::button('Delete', array(
					'onclick'=>CHtml::ajax(array(
						'type'=>'POST',
						'url'=>CController::createUrl('AjaxHtmlRemoveAccount', array('id'=>$journal->header->id,'index'=>$i)),
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
			<span id="total_debit">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $journal->totalDebit)); ?></td>
			</span>
		<td style="font-weight: bold; text-align: center">
			<span id="total_credit">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $journal->totalCredit)); ?></td>
			</span>
		<td></td>
		<td></td>
	</tr>
</table>