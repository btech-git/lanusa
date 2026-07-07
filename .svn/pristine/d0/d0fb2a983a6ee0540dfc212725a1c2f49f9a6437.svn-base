<?php
	$this->breadcrumbs = array(
		'Delivery'=>array('admin'),
		'Create',
	);
?>

<h1>Revisi Penyesuaian Barang</h1>

<div class="form">

	<?php echo CHtml::beginForm(); ?>

	<div class="container">
		<div class="span-12">
			<div class="row">
				<?php echo CHtml::label('Penyesuaian #', ''); ?>
				<span id="code_number">
				<?php echo CHtml::encode($adjustment->header->getCodeNumber(AdjustmentHeader::CN_CONSTANT)); ?>
				</span>	
			</div>

			<div class="row">
				<?php echo CHtml::label('Tanggal', ''); ?>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model' => $adjustment->header,
					'attribute' => 'date',
					// additional javascript options for the date picker plugin
					'options' => array(
						'dateFormat' => 'yy-mm-dd',
					),
					'htmlOptions' => array(
						'readonly' => true,
					),
				));
				?>
				<?php echo CHtml::error($adjustment->header, 'date'); ?>
			</div>

			<div>
				<?php echo CHtml::label('Gudang', ''); ?>
				<?php echo CHtml::activeDropDownList($adjustment->header, 'warehouse_id', CHtml::listData(Warehouse::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Warehouse --',
					'onchange' => CHtml::ajax(array(
						'type' => 'POST',
						'url' => CController::createUrl('ajaxHtmlUpdateAllProduct', array('id' => $adjustment->header->id)),
						'update' => '#detail_div',
					)),
				));?>
				<?php echo CHtml::error($adjustment->header, 'warehouse_id'); ?>
			</div>
		</div>

		<div class="span-12 last">
			<div class="row">
				<?php echo CHtml::activeLabelEx($adjustment->header, 'branch_id'); ?>
				<?php if ($adjustment->header->isNewRecord): ?>
					<?php echo CHtml::activeDropDownList($adjustment->header, 'branch_id', CHtml::listData(Branch::model()->findAll(array('order' => 't.name')), 'id', 'name'), array('empty' => '-- Pilih Perusahaan --',
						'onchange' => CHtml::ajax(array(
							'type' => 'POST',
							'dataType' => "JSON",
							'url' => CController::createUrl('ajaxJsonCodeNumber', array('id' => $adjustment->header->id)),
							'success' => 'function(data) {
								$("#code_number").html(data.codeNumber);
							}',
						)),
					)); ?>
					<?php echo CHtml::error($adjustment->header, 'branch_id'); ?>
				<?php else: ?>
					<?php echo CHtml::encode(CHtml::value($adjustment->header, 'branch.name')); ?>
				<?php endif;?>
			</div>

			<div class="row">
				<?php echo CHtml::label('Catatan', ''); ?>
				<?php echo CHtml::activeTextArea($adjustment->header, 'note', array('rows' => 5, 'cols' => 30)); ?>
				<?php echo CHtml::error($adjustment->header, 'note'); ?>
			</div>
		</div>
	</div>

	<hr />

	<div class="row">
		<?php echo CHtml::button('Cari Barang', array('name' => 'Search', 'onclick' => '$("#search-dialog").dialog("open"); return false;', 'onkeypress' => 'if (event.keyCode == 13) { $("#search-dialog").dialog("open"); return false; }')); ?>
		<?php echo CHtml::hiddenField('ProductId'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::error($adjustment->header, 'error'); ?>
   </div>
	
	<div id="detail_div">
		<?php $this->renderPartial('_detail', array('adjustment' => $adjustment)); ?>
	</div>

	<div class="row buttons">
        <?php echo CHtml::submitButton('Cancel', array('name' => 'Cancel', 'confirm' => 'Are you sure you want to cancel?')); ?>
		<?php echo CHtml::submitButton('Submit', array('name' => 'Submit', 'confirm' => 'Are you sure you want to save?')); ?>
	</div>

	<?php echo CHtml::endForm(); ?>

</div><!-- form -->

<div>
	<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		'id' => 'search-dialog',
		// additional javascript options for the dialog plugin
		'options' => array(
			'title' => 'Products',
			'autoOpen' => false,
			'width' => 'auto',
			'modal' => true,
		),
	)); ?>

	<div class="search-form">
		<div id="search_div">
			<?php $this->renderPartial('//site/_search', array(
				'model' => $product,
				'specificationList' => array(),
				'listData' => $this->listData(),
				'action' => CHtml::normalizeUrl(array('create')),
			)); ?>
		</div>
	</div>
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'product-grid',
		'dataProvider' => $dataProvider,
		'filter' => $product,
		'selectionChanged' => 'js:function(id) {
			$("#ProductId").val($.fn.yiiGridView.getSelection(id));
			$("#search-dialog").dialog("close");
			$.ajax({
				type: "POST",
				url: "' . CController::createUrl('ajaxHtmlAddProduct', array('id' => $adjustment->header->id, 'nt' => $adjustment->header->is_non_tax)) . '",
				data: $("form").serialize(),
				success: function(html) { $("#detail_div").html(html); },
			});
		}',
		'columns' => array(
			'name',
			array(
				'name' => 'unit_id',
				'filter' => CHtml::listData(Unit::model()->findAll(array('order' => 't.name')), 'id', 'name'),
				'value' => 'CHtml::value($data, "unit.name")',
			),
			'size',
		),
	)); ?>

	<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
</div>