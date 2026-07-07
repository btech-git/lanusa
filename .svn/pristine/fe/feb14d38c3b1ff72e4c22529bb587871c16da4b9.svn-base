<table style="border: 1px solid">
	<tr style="background-color: skyblue">
		<th style="text-align: center;">Tanda Terima #</th>
		<th style="text-align: center;">Tanggal</th>
		<th style="text-align: center;">Jatuh Tempo</th>
		<th style="text-align: center;">Total Invoice</th>
		<th style="text-align: center;">Bank</th>
		<th style="text-align: center;">Cheque Number</th>
		<th style="text-align: center;">Amount</th>
		<th></th>
	</tr>
	
	<?php //foreach ($saleReceipt->saleReceiptDetails as $i=>$detail): ?>
	<?php foreach ($saleCheque->details as $i=>$detail): ?>
	
<!--		<tr style="background-color: azure">
			<td>
				<?php //echo CHtml::activeHiddenField($detail, "[$i]sale_invoice_id"); ?>
				<?php //echo CHtml::encode($detail->saleInvoice->getCodeNumber(SaleInvoice::CN_CONSTANT)); ?>
			</td>
			<td style="text-align: center">
				<?php //echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleInvoice.date')))); ?>
			</td>
			
			<td style="text-align: right">
				<?php //echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'saleInvoice.totalInvoice'))); ?>
			</td>
			<td>
				<?php //echo CHtml::activeTextField($detail, "[$i]memo", array('size' => 30, 'maxLength' => 100)); ?>
			</td>
		</tr>
-->		
		<tr style="background-color: azure">
			<td><!--invoice #-->
				<?php echo CHtml::activeHiddenField($detail, "[$i]sale_receipt_header_id"); ?>
				<?php echo CHtml::encode($detail->saleReceiptHeader->getCodeNumber(SaleReceiptHeader::CN_CONSTANT)); ?>
			</td>
			
			<td style="text-align: center"><!--tanggal-->
				<?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleReceiptHeader.date')))); ?>
			</td>
			
			<td style="text-align: center"><!--jatuh tempo-->
				<?php echo CHtml::encode(Yii::app()->dateFormatter->format('d MMMM yyyy', strtotime(CHtml::value($detail, 'saleReceiptHeader.due_date')))); ?>
			</td>
			
			<td style="text-align: right"><!--total invoice-->
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', CHtml::value($detail, 'saleReceiptHeader.totalInvoice'))); ?>
			</td>
			
			<td><!--bank-->
				<?php echo CHtml::activeTextField($detail, "[$i]bank", array('size' => 20, 'maxLength' => 60)); ?>
			</td>
			
			<td><!--cheque number-->
				<?php echo CHtml::activeTextField($detail, "[$i]cheque_number", array('size' => 30, 'maxLength' => 60)); ?>
			</td>
			
			<td><!--amount-->
				<?php echo CHtml::activeTextField($detail, "[$i]amount", array('size' => 21, 'maxLength' => 21, 
					'onchange' => CHtml::ajax(array(
						'type' => 'POST',
						'dataType' => "JSON",
						'url' => CController::createUrl('ajaxJsonAmount', array('id' => $saleCheque->header->id)),
						'success' => 'function(data) {
							$("#amount_div").html(data.amount);
						}',
					))
				)); ?>
			</td>
			
			<td style="width: 5%">
				<?php if ($detail->isNewRecord): ?>
					<?php echo CHtml::button('Delete', array(
						'onclick'=>CHtml::ajax(array(
							'type'=>'POST',
							'url'=>CController::createUrl('ajaxHtmlRemoveDetail', array('id' => $saleCheque->header->id, 'index' => $i)),
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
		<td></td>
		<td style="text-align: right; font-weight: bold">Total</td>
		<td style="text-align: right; font-weight: bold"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleCheque->getTotalSaleReceipt())); ?></td>
		<td></td>
		<td></td>
		<td>
			<span id="amount_div">
				<?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0', $saleCheque->header->getTotalAmount())); ?>
			</span>
					</td>
		<td></td>
	</tr>
</table>
