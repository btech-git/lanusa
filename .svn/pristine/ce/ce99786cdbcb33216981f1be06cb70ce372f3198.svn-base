<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#StartDate").val("' . $startDate . '");
	$("#EndDate").val("' . $endDate . '");
	$("#PageSize").val("' . $stockSummary->dataProvider->pagination->pageSize . '");
	$("#CurrentPage").val("' . ($stockSummary->dataProvider->pagination->getCurrentPage(false) + 1) . '");
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
            Kategori
            <?php //echo CHtml::activeDropDownlist($product, 'category_id', CHtml::listData(Category::model()->findAll(), 'id', 'name'), array('empty'=>'-- Semua Kategori --')); ?>
            <?php
            echo CHtml::dropDownList('category', 'id', $listDataCategory, array(
                'empty' => '-Pilih Kategori-',
                'options' => array($category => array('selected' => TRUE)))); //to preserve the selected value
            ?>		
        </div>

        <div class="row">
            Jumlah per Halaman
<?php echo CHtml::textField('PageSize', '', array('size' => 3)); ?>

            Halaman saat ini
<?php echo CHtml::textField('page', '', array('size' => 3, 'id' => 'CurrentPage')); ?>
        </div>

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

    <div class="right"><?php //echo ReportHelper::summaryText($stockSummary->dataProvider);  ?></div>
    <div class="clear"></div>
    <div class="right">
    <?php echo ReportHelper::sortText($stockSummary->dataProvider->sort, array('Nama Produk', 'Kategori')); ?>
    </div>
    <div class="clear"></div>
</div>

<div>
        <?php $this->renderPartial('_summary', array('stockSummary' => $stockSummary, 'startDate' => $startDate, 'endDate' => $endDate)); ?>
</div>

<div class="hide">
    <div class="right">
<?php
$this->widget('system.web.widgets.pagers.CLinkPager', array(
    'itemCount' => $stockSummary->dataProvider->pagination->itemCount,
    'pageSize' => $stockSummary->dataProvider->pagination->pageSize,
    'currentPage' => $stockSummary->dataProvider->pagination->getCurrentPage(false),
));
?>
    </div>
    <div class="clear"></div>
</div>