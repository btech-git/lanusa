<div id="supplier_div" class="row">
    <?php echo CHtml::dropDownlist('SupplierId', $supplierId, CHtml::listData($suppliers, 'id', 'company'), array(
        'empty'=>'-- Semua --'
    )); ?>
</div>