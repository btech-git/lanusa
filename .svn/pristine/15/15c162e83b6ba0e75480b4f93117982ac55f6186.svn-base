<?php
Yii::app()->clientScript->registerScript('report', '
	$("#header").addClass("hide");
	$("#mainmenu").addClass("hide");
	$(".breadcrumbs").addClass("hide");
	$("#footer").addClass("hide");

	$("#PageSize").val("' . $hppSummary->dataProvider->pagination->pageSize . '");
	$("#CurrentPage").val("' . ($hppSummary->dataProvider->pagination->getCurrentPage(false) + 1) . '");
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
            <?php echo CHtml::activeDropDownlist($product, 'category_id', CHtml::listData(Category::model()->findAll(), 'id', 'name'), array('empty' => '-- Semua Kategori --')); ?>
        </div>

        <div class="row">
            Jumlah per Halaman
            <?php echo CHtml::textField('PageSize', '', array('size' => 3)); ?>

            Halaman saat ini
            <?php echo CHtml::textField('page', '', array('size' => 3, 'id' => 'CurrentPage')); ?>
        </div>

        <div class="row">
            <?php echo CHtml::hiddenField('sort', '', array('id' => 'CurrentSort')); ?>
        </div>



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

    <div class="right"><?php echo ReportHelper::summaryText($hppSummary->dataProvider); ?></div>
    <div class="clear"></div>
    <div class="right">
        <?php echo ReportHelper::sortText($hppSummary->dataProvider->sort, array('Nama Produk', 'Kategori')); ?>
    </div>
    <div class="clear"></div>
</div>

<div>
    <?php $this->renderPartial('_summary', array('hppSummary' => $hppSummary)); ?>
</div>

<div class="hide">
    <div class="right">
        <?php
        $this->widget('system.web.widgets.pagers.CLinkPager', array(
            'itemCount' => $hppSummary->dataProvider->pagination->itemCount,
            'pageSize' => $hppSummary->dataProvider->pagination->pageSize,
            'currentPage' => $hppSummary->dataProvider->pagination->getCurrentPage(false),
        ));
        ?>
    </div>
    <div class="clear"></div>
</div>