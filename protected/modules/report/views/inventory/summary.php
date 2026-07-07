<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("' . $startDate . '");
	$("#EndDate").val("' . $endDate . '");
	$("#PageSize").val("' . $inventorySummary->dataProvider->pagination->pageSize . '");
	$("#CurrentPage").val("' . ($inventorySummary->dataProvider->pagination->getCurrentPage(false) + 1) . '");
	$("#CurrentSort").val("' . $currentSort . '");
');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/css/transaction/report.css');
?>

<div class="hide">
    <div class="form" style="text-align: center">

        <?php echo CHtml::beginForm(array(''), 'get'); ?>

        <div class="row" style="background-color: #DFDFDF">
            Nama Produk
            <?php echo CHtml::activeTextField($product, 'name'); ?>
            Ukuran
            <?php echo CHtml::activeTextField($product, 'size'); ?>
            Kategori
            <?php echo CHtml::activeDropDownlist($product, 'category_id', CHtml::listData(Category::model()->findAll(), 'id', 'name'), array('empty' => '-- Semua Kategori --')); ?>
        </div>

        <div class="row" style="background-color: #DFDFDF">
            Warehouse
            <?php
            echo CHtml::dropDownList('WarehouseId', $warehouseId,
                    CHtml::listData(Warehouse::model()->findAll(), 'id', 'name'),
                    array('empty' => '-Pilih Warehouse-'));
            ?>	
        </div>

        <!--			<div class="row">
                                        Jumlah per Halaman
        <?php /* echo CHtml::textField('PageSize', '', array('size'=>3)); ?>

          Halaman saat ini
          <?php echo CHtml::textField('page', '', array('size'=>3, 'id'=>'CurrentPage')); */ ?>
                                </div>-->

        <div class="row">
            Tanggal Mulai
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'StartDate',
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth'=>true,
                    'changeYear'=>true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                ),
            ));
            ?>

            Sampai
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'EndDate',
                'options' => array(
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth'=>true,
                    'changeYear'=>true,
                ),
                'htmlOptions' => array(
                    'readonly' => true,
                ),
            ));
            ?>
        </div>

        <div class="row">
        <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
        </div>
        <!--		
                        <div class="row">
                                Cabang
<?php
//				echo CHtml::dropDownList('branch', 'id', $listData, array(
//					'empty' => '-Pilih Cabang-', 
//					'options' => array($branch => array('selected' => TRUE)))); //to preserve the selected value
?>				
                        </div>
        -->	
        <div class="row button">
            <?php echo CHtml::submitButton('Show', array('onclick' => '$("#CurrentSort").val(""); return true;')); ?>
<?php echo CHtml::resetButton('Clear'); ?>
        </div>

        <div class="row button">
<?php echo CHtml::submitButton('Save to Excel', array('name' => 'SaveExcel')); ?>
        </div>

<?php echo CHtml::endForm(); ?>

    </div>

    <hr />

    <div class="right"><?php //echo ReportHelper::summaryText($inventorySummary->dataProvider);  ?></div>
    <div class="clear"></div>
    <div class="right">
    <?php //echo ReportHelper::sortText($inventorySummary->dataProvider->sort, array('Nama Produk', 'Kategori'));  ?>
    </div>
    <div class="clear"></div>
</div>

<div>
        <?php $this->renderPartial('_summary', array('inventorySummary' => $inventorySummary, 'startDate' => $startDate, 'endDate' => $endDate, 'warehouseId' => $warehouseId)); ?>
</div>

<div class="hide">
    <div class="right">
<?php
$this->widget('system.web.widgets.pagers.CLinkPager', array(
    'itemCount' => $inventorySummary->dataProvider->pagination->itemCount,
    'pageSize' => $inventorySummary->dataProvider->pagination->pageSize,
    'currentPage' => $inventorySummary->dataProvider->pagination->getCurrentPage(false),
));
?>
    </div>
    <div class="clear"></div>
</div>