<?php
Yii::app()->clientScript->registerCss('_report', '
        .width1-1 { width: 16% }
        .width1-2 { width: 16% }
        .width1-3 { width: 16% }
        .width1-4 { width: 16% }
        .width1-5 { width: 16% }
		.width1-6 { width: 16% }
        
        .width2-1 { width: 16% }
        .width2-2 { width: 16% }
        .width2-3 { width: 16% }
        .width2-4 { width: 16% }
		.width2-5 { width: 16% }
		.width2-6 { width: 16% }
');
?>

<div style="font-weight: bold; text-align: center">
	<div style="font-size: larger"><?php echo CHtml::encode(CHtml::value($branch, 'name')); ?></div>
	<div style="font-size: larger">Laporan History</div>
	<div><?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($startDate))) . ' &nbsp;&ndash;&nbsp; ' . CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime($endDate))); ?></div>
</div>

<br />

<table class="report">
	<tr id="header1">
		<th class="width1-1">Waktu #</th>
		<th class="width1-2">Nama Tabel</th>
		<th class="width1-3">record_id</th>
		<th class="width1-4">old_data</th>
		<th class="width1-5">new_data</th>
		<th class="width1-6">user_id</th>
	</tr>
	<tr>
		<td class="width2-1" style="border-top: 1px solid"></td>
		<td class="width2-2" style="border-top: 1px solid"></td>
		<td class="width2-3" style="border-top: 1px solid"></td>
		<td class="width2-4" style="border-top: 1px solid"></td>
		<td class="width2-5" style="border-top: 1px solid"></td>
		<td class="width2-6" style="border-top: 1px solid"></td>
	</tr>
	<?php foreach ($dataProvider->data as $header): ?>
		<tr class="items1">
			<td class="width1-1"><?php echo CHtml::encode(CHtml::value($header, 'time')); ?></td>
			<td class="width1-2" style="text-align: left"><?php echo CHtml::encode(CHtml::value($header, 'table_name')); ?></td>
			<td class="width1-3" style="text-align: left"><?php echo CHtml::encode(CHtml::value($header, 'record_id')); ?></td>
			<td class="width1-4" style="text-align: left"><?php echo CHtml::encode(CHtml::value($header, 'old_data')); ?></td>
			<td class="width1-5" style="text-align: left"><?php echo CHtml::encode(CHtml::value($header, 'new_data')); ?></td>
<!--			<td class="width1-6" style="text-align: left"><?php //echo CHtml::encode(CHtml::value($header, 'user_data')); ?></td>-->
		</tr>
	<?php endforeach; ?>
</table>